<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Setup';
$messages = [];
$errors = [];

$seedCategories = [
    ['Sedans', 'Comfortable daily-drive cars for city and highway use.'],
    ['SUVs', 'Family-ready vehicles with flexible space and road presence.'],
    ['Pickup Trucks', 'Work-capable vehicles for hauling and outdoor trips.'],
    ['Electric Cars', 'Efficient electric units for modern buyers.'],
];

$seedProducts = [
    ['Sedans', 'SED-001', 'Astra LX Sedan', 2023, 12000, 'Automatic', 'Gasoline', 'Graphite Gray', 'A clean sedan with a quiet cabin, efficient engine, and complete service history.', 1688000.00, 3],
    ['SUVs', 'SUV-002', 'Northline Sport SUV', 2022, 18500, 'Automatic', 'Diesel', 'Pearl White', 'A spacious SUV with seven-seat capacity, parking sensors, and strong long-drive comfort.', 2395000.00, 2],
    ['Pickup Trucks', 'TRK-003', 'Hauler Pro Pickup', 2021, 24000, 'Manual', 'Diesel', 'Jet Black', 'A durable pickup with cargo bed liner, tow-ready stance, and practical worksite utility.', 1890000.00, 4],
    ['Electric Cars', 'EV-004', 'VoltEdge EV Hatch', 2024, 5200, 'Single Speed', 'Electric', 'Arctic Silver', 'A smooth electric hatch with quick acceleration, low running cost, and modern driver displays.', 2140000.00, 2],
];

try {
    $pdo = db();

    $adminCount = (int) $pdo->query('SELECT COUNT(*) FROM users WHERE role = "admin"')->fetchColumn();
    if ($adminCount === 0) {
        $stmt = $pdo->prepare(
            'INSERT INTO users
            (complete_name, email, password_hash, complete_address, contact_numbers, role, email_verified, confirmation_token, status, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, "admin", 1, NULL, "active", NOW(), NOW())'
        );
        $stmt->execute([
            'Error 404 Admin',
            'admin@404motors.local',
            password_hash('Admin123!', PASSWORD_DEFAULT),
            '404 Motors Exchange Main Office',
            '+63 900 404 0404',
        ]);
        $messages[] = 'Default seller admin created.';
    } else {
        $messages[] = 'Seller admin account already exists.';
    }

    foreach ($seedCategories as [$name, $description]) {
        $stmt = $pdo->prepare(
            'INSERT INTO categories (name, description)
             VALUES (?, ?)
             ON DUPLICATE KEY UPDATE description = VALUES(description)'
        );
        $stmt->execute([$name, $description]);
    }
    $messages[] = 'Vehicle categories are ready.';

    foreach ($seedProducts as $seed) {
        [$categoryName, $sku, $name, $year, $mileage, $transmission, $fuel, $color, $description, $price, $stock] = $seed;
        $categoryStmt = $pdo->prepare('SELECT id FROM categories WHERE name = ? LIMIT 1');
        $categoryStmt->execute([$categoryName]);
        $categoryId = (int) $categoryStmt->fetchColumn();

        $stmt = $pdo->prepare(
            'INSERT INTO products
            (category_id, sku, name, model_year, mileage, transmission, fuel_type, color, description, image_url,
             price, stock_quantity, status, created_by, updated_by, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, "assets/hero-red-car.jpg", ?, ?, "active", NULL, NULL, NOW(), NOW())
             ON DUPLICATE KEY UPDATE price = VALUES(price), stock_quantity = VALUES(stock_quantity), updated_at = NOW()'
        );
        $stmt->execute([$categoryId, $sku, $name, $year, $mileage, $transmission, $fuel, $color, $description, $price, $stock]);
    }
    $messages[] = 'Sample car inventory is ready.';

    $pdo->prepare(
        'INSERT INTO audit_logs (user_id, action, table_name, record_id, details, ip_address, created_at)
         VALUES (NULL, "Ran setup", "users", NULL, "Initial setup and seed data checked", ?, NOW())'
    )->execute([$_SERVER['REMOTE_ADDR'] ?? 'local']);
} catch (Throwable $error) {
    $errors[] = $error->getMessage();
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="page-title">
    <p class="eyebrow">Project setup</p>
    <h1>404 Motors Exchange</h1>
    <p>Run this after importing sql/schema.sql into MySQL.</p>
</section>

<section class="section narrow">
    <?php if ($errors): ?>
        <div class="form-errors">
            <p>Setup could not run. Confirm that the database exists and config.php matches your MySQL account.</p>
            <?php foreach ($errors as $error): ?>
                <p><?= h($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="notice-card success">
            <h2>Setup complete</h2>
            <?php foreach ($messages as $message): ?>
                <p><?= h($message) ?></p>
            <?php endforeach; ?>
            <p><strong>Admin e-mail:</strong> admin@404motors.local</p>
            <p><strong>Admin password:</strong> Admin123!</p>
            <a class="button" href="<?= h(url_for('login.php')) ?>">Go to Login</a>
        </div>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
