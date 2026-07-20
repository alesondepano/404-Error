<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/functions.php';

$admin = require_admin();
$pageTitle = 'Manage Stocks';
$keyword = trim((string) ($_GET['search'] ?? ''));
$products = [];

try {
    $sql = 'SELECT p.*, c.name AS category_name
            FROM products p
            JOIN categories c ON c.id = p.category_id';
    $params = [];

    if ($keyword !== '') {
        $sql .= ' WHERE p.name LIKE ? OR p.sku LIKE ? OR c.name LIKE ?';
        $term = '%' . $keyword . '%';
        $params = [$term, $term, $term];
    }

    $sql .= ' ORDER BY c.name, p.name';
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    $products = $stmt->fetchAll();
} catch (Throwable $error) {
    flash('error', 'Stocks failed to load: ' . $error->getMessage());
}

require_once __DIR__ . '/../includes/header.php';
?>

<section class="page-title admin-title">
    <p class="eyebrow">Seller part</p>
    <h1>Stocks and Prices</h1>
    <p>Add or modify cars sold on the website.</p>
    <a class="button" href="<?= h(url_for('admin/product_form.php')) ?>">Add Vehicle</a>
</section>

<section class="section">
    <form class="toolbar" method="get" action="<?= h(url_for('admin/products.php')) ?>">
        <label>
            Search
            <input type="search" name="search" value="<?= h($keyword) ?>" placeholder="SKU, name, category">
        </label>
        <button class="button" type="submit">Search</button>
    </form>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Vehicle</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $row): ?>
                    <tr>
                        <td><?= h($row['sku']) ?></td>
                        <td><?= h($row['name']) ?></td>
                        <td><?= h($row['category_name']) ?></td>
                        <td><?= h(format_peso($row['price'])) ?></td>
                        <td><?= h($row['stock_quantity']) ?></td>
                        <td><?= h($row['status']) ?></td>
                        <td><a class="button small secondary" href="<?= h(url_for('admin/product_form.php?id=' . $row['id'])) ?>">Edit</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

