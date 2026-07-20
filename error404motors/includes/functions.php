<?php
declare(strict_types=1);

require_once __DIR__ . '/../config.php';

function db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    return $pdo;
}

function h($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function app_base_path(): string
{
    $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    $base = preg_replace('#/[^/]*$#', '', $script) ?: '';

    if (substr($base, -6) === '/admin') {
        $base = substr($base, 0, -6);
    }

    return $base === '/' ? '' : $base;
}

function url_for(string $path = ''): string
{
    return app_base_path() . '/' . ltrim($path, '/');
}

function full_url_for(string $path): string
{
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

    return $scheme . '://' . $host . url_for($path);
}

function redirect(string $path): void
{
    header('Location: ' . url_for($path));
    exit;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . h(csrf_token()) . '">';
}

function verify_csrf(): void
{
    $sent = $_POST['csrf_token'] ?? '';

    if (!is_string($sent) || !hash_equals($_SESSION['csrf_token'] ?? '', $sent)) {
        flash('error', 'The form expired. Please try again.');
        redirect('index.php');
    }
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function consume_flash(): array
{
    $messages = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);

    return $messages;
}

function current_user(): ?array
{
    if (empty($_SESSION['user_id'])) {
        return null;
    }

    static $cachedId = null;
    static $cachedUser = null;

    if ($cachedId === $_SESSION['user_id']) {
        return $cachedUser;
    }

    try {
        $stmt = db()->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
        $stmt->execute([$_SESSION['user_id']]);
        $cachedUser = $stmt->fetch() ?: null;
        $cachedId = $_SESSION['user_id'];
    } catch (Throwable) {
        return null;
    }

    return $cachedUser;
}

function require_login(): array
{
    $user = current_user();

    if (!$user) {
        flash('error', 'Please log in first.');
        redirect('login.php');
    }

    if ($user['status'] !== 'active') {
        flash('error', 'Your account is disabled. Please contact the seller admin.');
        redirect('logout.php');
    }

    return $user;
}

function require_admin(): array
{
    $user = require_login();

    if ($user['role'] !== 'admin') {
        flash('error', 'Seller admin access is required.');
        redirect('index.php');
    }

    return $user;
}

function log_activity(string $action, ?string $tableName = null, $recordId = null, $details = null): void
{
    try {
        $encodedDetails = is_array($details) ? json_encode($details, JSON_UNESCAPED_SLASHES) : $details;
        $stmt = db()->prepare(
            'INSERT INTO audit_logs (user_id, action, table_name, record_id, details, ip_address, created_at)
             VALUES (?, ?, ?, ?, ?, ?, NOW())'
        );
        $stmt->execute([
            $_SESSION['user_id'] ?? null,
            $action,
            $tableName,
            $recordId,
            $encodedDetails,
            $_SERVER['REMOTE_ADDR'] ?? 'local',
        ]);
    } catch (Throwable) {
        // Audit logging should not break the user-facing workflow.
    }
}

function format_audit_details($details): string
{
    $text = trim((string) $details);

    if ($text === '') {
        return '-';
    }

    $decoded = json_decode($text, true);

    if (!is_array($decoded) || json_last_error() !== JSON_ERROR_NONE) {
        return $text;
    }

    $formatted = [];

    foreach ($decoded as $key => $value) {
        $label = ucfirst(str_replace('_', ' ', (string) $key));

        if (is_bool($value)) {
            $value = $value ? 'Yes' : 'No';
        } elseif ($value === null) {
            $value = '-';
        } elseif (is_array($value)) {
            $value = json_encode($value, JSON_UNESCAPED_SLASHES) ?: '-';
        } else {
            $value = (string) $value;
        }

        if ((string) $key === 'role') {
            $value = ucfirst(strtolower($value));
        }

        $formatted[] = $label . ': ' . $value;
    }

    return implode(', ', $formatted);
}

function send_confirmation_email(string $email, string $name, string $token): bool
{
    $confirmUrl = full_url_for('confirm.php?token=' . urlencode($token));
    $subject = APP_NAME . ' email confirmation';
    $message = "Hello {$name},\n\n";
    $message .= "Please confirm your buyer account by opening this link:\n{$confirmUrl}\n\n";
    $message .= "Thank you,\n" . COMPANY_NAME;
    $headers = "From: " . MAIL_FROM . "\r\nContent-Type: text/plain; charset=UTF-8";
    $sent = false;

    if (function_exists('mail')) {
        $sent = @mail($email, $subject, $message, $headers);
    }

    $storagePath = __DIR__ . '/../storage';
    if (!is_dir($storagePath)) {
        mkdir($storagePath, 0775, true);
    }

    file_put_contents(
        $storagePath . '/mail_log.txt',
        '[' . date('Y-m-d H:i:s') . "] To: {$email}\nSubject: {$subject}\n{$message}\n---\n",
        FILE_APPEND
    );

    return $sent;
}

function valid_contact_number(string $value): bool
{
    return (bool) preg_match('/^[0-9+\-\s().]{7,30}$/', $value);
}

function format_peso($amount): string
{
    return 'PHP ' . number_format((float) $amount, 2);
}

function categories(): array
{
    return db()->query('SELECT * FROM categories ORDER BY name')->fetchAll();
}

function featured_products(int $limit = 3): array
{
    $stmt = db()->prepare(
        'SELECT p.*, c.name AS category_name
         FROM products p
         JOIN categories c ON c.id = p.category_id
         WHERE p.status = "active" AND p.stock_quantity > 0
         ORDER BY p.created_at DESC
         LIMIT ?'
    );
    $stmt->bindValue(1, $limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll();
}

function search_products(?int $categoryId, string $keyword = ''): array
{
    $sql = 'SELECT p.*, c.name AS category_name
            FROM products p
            JOIN categories c ON c.id = p.category_id
            WHERE p.status = "active" AND p.stock_quantity > 0';
    $params = [];

    if ($categoryId) {
        $sql .= ' AND p.category_id = ?';
        $params[] = $categoryId;
    }

    if ($keyword !== '') {
        $sql .= ' AND (p.name LIKE ? OR p.sku LIKE ? OR p.description LIKE ? OR c.name LIKE ?)';
        $term = '%' . $keyword . '%';
        array_push($params, $term, $term, $term, $term);
    }

    $sql .= ' ORDER BY c.name, p.name';
    $stmt = db()->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll();
}

function find_product(int $id): ?array
{
    $stmt = db()->prepare(
        'SELECT p.*, c.name AS category_name
         FROM products p
         JOIN categories c ON c.id = p.category_id
         WHERE p.id = ?
         LIMIT 1'
    );
    $stmt->execute([$id]);

    return $stmt->fetch() ?: null;
}

function cart_count(): int
{
    return array_sum($_SESSION['cart'] ?? []);
}

function add_to_cart(int $productId, int $quantity): void
{
    $quantity = max(1, $quantity);
    $_SESSION['cart'][$productId] = ($_SESSION['cart'][$productId] ?? 0) + $quantity;
}

function update_cart_item(int $productId, int $quantity): void
{
    if ($quantity <= 0) {
        unset($_SESSION['cart'][$productId]);
        return;
    }

    $_SESSION['cart'][$productId] = $quantity;
}

function cart_details(): array
{
    $cart = $_SESSION['cart'] ?? [];

    if (!$cart) {
        return ['items' => [], 'subtotal' => 0.0];
    }

    $ids = array_map('intval', array_keys($cart));
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = db()->prepare(
        "SELECT p.*, c.name AS category_name
         FROM products p
         JOIN categories c ON c.id = p.category_id
         WHERE p.id IN ({$placeholders})"
    );
    $stmt->execute($ids);
    $products = $stmt->fetchAll();
    $items = [];
    $subtotal = 0.0;

    foreach ($products as $product) {
        $quantity = (int) ($cart[(int) $product['id']] ?? 0);
        if ($quantity < 1) {
            continue;
        }

        $lineTotal = $quantity * (float) $product['price'];
        $product['cart_quantity'] = $quantity;
        $product['line_total'] = $lineTotal;
        $items[] = $product;
        $subtotal += $lineTotal;
    }

    return ['items' => $items, 'subtotal' => $subtotal];
}

function product_image(array $product): string
{
    $image = trim((string) ($product['image_url'] ?? ''));

    return $image !== '' ? $image : 'assets/hero-red-car.jpg';
}

function vehicle_specs(array $product): array
{
    return [
        'Year' => $product['model_year'] ?: 'N/A',
        'Mileage' => number_format((int) ($product['mileage'] ?? 0)) . ' km',
        'Transmission' => $product['transmission'] ?: 'N/A',
        'Fuel' => $product['fuel_type'] ?: 'N/A',
        'Color' => $product['color'] ?: 'N/A',
    ];
}

function site_metrics(): array
{
    return [
        'cars' => (int) db()->query('SELECT COALESCE(SUM(stock_quantity), 0) FROM products WHERE status = "active"')->fetchColumn(),
        'categories' => (int) db()->query('SELECT COUNT(*) FROM categories')->fetchColumn(),
        'orders' => (int) db()->query('SELECT COUNT(*) FROM orders')->fetchColumn(),
    ];
}
