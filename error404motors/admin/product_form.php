<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/functions.php';

$admin = require_admin();
$pageTitle = 'Vehicle Form';
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$editing = $id > 0;
$errors = [];
$allCategories = categories();
$values = [
    'category_id' => $allCategories[0]['id'] ?? '',
    'sku' => '',
    'name' => '',
    'model_year' => date('Y'),
    'mileage' => 0,
    'transmission' => 'Automatic',
    'fuel_type' => 'Gasoline',
    'color' => '',
    'description' => '',
    'image_url' => 'assets/hero-red-car.jpg',
    'price' => '0.00',
    'stock_quantity' => 1,
    'status' => 'active',
];

if ($editing) {
    $stmt = db()->prepare('SELECT * FROM products WHERE id = ? LIMIT 1');
    $stmt->execute([$id]);
    $found = $stmt->fetch();

    if (!$found) {
        flash('error', 'Vehicle not found.');
        redirect('admin/products.php');
    }

    $values = array_merge($values, $found);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $values = [
        'category_id' => (int) ($_POST['category_id'] ?? 0),
        'sku' => strtoupper(trim((string) ($_POST['sku'] ?? ''))),
        'name' => trim((string) ($_POST['name'] ?? '')),
        'model_year' => (int) ($_POST['model_year'] ?? date('Y')),
        'mileage' => max(0, (int) ($_POST['mileage'] ?? 0)),
        'transmission' => trim((string) ($_POST['transmission'] ?? '')),
        'fuel_type' => trim((string) ($_POST['fuel_type'] ?? '')),
        'color' => trim((string) ($_POST['color'] ?? '')),
        'description' => trim((string) ($_POST['description'] ?? '')),
        'image_url' => trim((string) ($_POST['image_url'] ?? '')),
        'price' => (float) ($_POST['price'] ?? 0),
        'stock_quantity' => max(0, (int) ($_POST['stock_quantity'] ?? 0)),
        'status' => (string) ($_POST['status'] ?? 'active'),
    ];

    if ($values['category_id'] < 1) {
        $errors[] = 'Category is required.';
    }

    if ($values['sku'] === '') {
        $errors[] = 'SKU is required.';
    }

    if ($values['name'] === '') {
        $errors[] = 'Vehicle name is required.';
    }

    if ($values['model_year'] < 1990 || $values['model_year'] > (int) date('Y') + 1) {
        $errors[] = 'Model year is outside the accepted range.';
    }

    if ($values['price'] <= 0) {
        $errors[] = 'Price must be greater than zero.';
    }

    if (!in_array($values['status'], ['active', 'inactive'], true)) {
        $errors[] = 'Status is invalid.';
    }

    if (!$errors) {
        $stmt = db()->prepare('SELECT id FROM products WHERE sku = ? AND id <> ? LIMIT 1');
        $stmt->execute([$values['sku'], $id]);

        if ($stmt->fetch()) {
            $errors[] = 'SKU is already used.';
        }
    }

    if (!$errors) {
        if ($editing) {
            $stmt = db()->prepare(
                'UPDATE products
                 SET category_id = ?, sku = ?, name = ?, model_year = ?, mileage = ?, transmission = ?,
                     fuel_type = ?, color = ?, description = ?, image_url = ?, price = ?, stock_quantity = ?,
                     status = ?, updated_by = ?, updated_at = NOW()
                 WHERE id = ?'
            );
            $stmt->execute([
                $values['category_id'],
                $values['sku'],
                $values['name'],
                $values['model_year'],
                $values['mileage'],
                $values['transmission'],
                $values['fuel_type'],
                $values['color'],
                $values['description'],
                $values['image_url'],
                $values['price'],
                $values['stock_quantity'],
                $values['status'],
                $admin['id'],
                $id,
            ]);
            log_activity('Updated vehicle stock', 'products', $id, ['sku' => $values['sku'], 'price' => $values['price'], 'stock' => $values['stock_quantity']]);
            flash('success', 'Vehicle updated.');
        } else {
            $stmt = db()->prepare(
                'INSERT INTO products
                (category_id, sku, name, model_year, mileage, transmission, fuel_type, color, description, image_url,
                 price, stock_quantity, status, created_by, updated_by, created_at, updated_at)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())'
            );
            $stmt->execute([
                $values['category_id'],
                $values['sku'],
                $values['name'],
                $values['model_year'],
                $values['mileage'],
                $values['transmission'],
                $values['fuel_type'],
                $values['color'],
                $values['description'],
                $values['image_url'],
                $values['price'],
                $values['stock_quantity'],
                $values['status'],
                $admin['id'],
                $admin['id'],
            ]);
            $newId = (int) db()->lastInsertId();
            log_activity('Created vehicle stock', 'products', $newId, ['sku' => $values['sku'], 'price' => $values['price'], 'stock' => $values['stock_quantity']]);
            flash('success', 'Vehicle created.');
        }

        redirect('admin/products.php');
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<section class="page-title admin-title">
    <p class="eyebrow">Seller part</p>
    <h1><?= $editing ? 'Edit Vehicle' : 'Add Vehicle' ?></h1>
</section>

<section class="section narrow">
    <?php if ($errors): ?>
        <div class="form-errors">
            <?php foreach ($errors as $error): ?>
                <p><?= h($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form class="panel-form" method="post" action="<?= h(url_for('admin/product_form.php' . ($editing ? '?id=' . $id : ''))) ?>">
        <?= csrf_field() ?>
        <div class="form-grid">
            <label>
                Category
                <select name="category_id" required>
                    <?php foreach ($allCategories as $category): ?>
                        <option value="<?= h($category['id']) ?>" <?= (int) $values['category_id'] === (int) $category['id'] ? 'selected' : '' ?>>
                            <?= h($category['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>
                SKU
                <input type="text" name="sku" value="<?= h($values['sku']) ?>" required>
            </label>
        </div>
        <label>
            Vehicle name
            <input type="text" name="name" value="<?= h($values['name']) ?>" required>
        </label>
        <div class="form-grid">
            <label>
                Model year
                <input type="number" name="model_year" value="<?= h($values['model_year']) ?>" min="1990" max="<?= h((int) date('Y') + 1) ?>" required>
            </label>
            <label>
                Mileage
                <input type="number" name="mileage" value="<?= h($values['mileage']) ?>" min="0" required>
            </label>
        </div>
        <div class="form-grid">
            <label>
                Transmission
                <input type="text" name="transmission" value="<?= h($values['transmission']) ?>" required>
            </label>
            <label>
                Fuel type
                <input type="text" name="fuel_type" value="<?= h($values['fuel_type']) ?>" required>
            </label>
        </div>
        <label>
            Color
            <input type="text" name="color" value="<?= h($values['color']) ?>" required>
        </label>
        <label>
            Description
            <textarea name="description" rows="4" required><?= h($values['description']) ?></textarea>
        </label>
        <label>
            Image URL
            <input type="text" name="image_url" value="<?= h($values['image_url']) ?>">
        </label>
        <div class="form-grid">
            <label>
                Price
                <input type="number" name="price" step="0.01" min="1" value="<?= h($values['price']) ?>" required>
            </label>
            <label>
                Stock quantity
                <input type="number" name="stock_quantity" min="0" value="<?= h($values['stock_quantity']) ?>" required>
            </label>
        </div>
        <label>
            Status
            <select name="status">
                <option value="active" <?= $values['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= $values['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </label>
        <div class="form-actions">
            <button class="button" type="submit">Save Vehicle</button>
            <a class="button secondary" href="<?= h(url_for('admin/products.php')) ?>">Cancel</a>
        </div>
    </form>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
