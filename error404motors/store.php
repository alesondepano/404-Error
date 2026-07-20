<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $productId = (int) ($_POST['product_id'] ?? 0);
    $quantity = max(1, (int) ($_POST['quantity'] ?? 1));
    $product = find_product($productId);

    if (!$product || $product['status'] !== 'active' || (int) $product['stock_quantity'] < 1) {
        flash('error', 'That vehicle is not available.');
    } else {
        add_to_cart($productId, min($quantity, (int) $product['stock_quantity']));
        log_activity('Added product to cart', 'products', $productId, ['quantity' => $quantity]);
        flash('success', $product['name'] . ' was added to your cart.');
    }

    redirect('store.php');
}

$pageTitle = 'Store';
$categoryId = isset($_GET['category']) ? (int) $_GET['category'] : null;
$keyword = trim((string) ($_GET['search'] ?? ''));
$allCategories = [];
$products = [];
$dbError = null;

try {
    $allCategories = categories();
    $products = search_products($categoryId, $keyword);
} catch (Throwable $error) {
    $dbError = $error->getMessage();
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="page-title">
    <p class="eyebrow">Buyer part</p>
    <h1>Store</h1>
    <p>Choose from categorized vehicles and add units to your cart.</p>
</section>

<?php if ($dbError): ?>
    <section class="section narrow">
        <div class="notice-card">
            <h2>Database connection needed</h2>
            <p>Import the SQL file and confirm your MySQL settings in config.php.</p>
        </div>
    </section>
<?php else: ?>
    <section class="section">
        <form class="toolbar" method="get" action="<?= h(url_for('store.php')) ?>">
            <label>
                Category
                <select name="category">
                    <option value="">All categories</option>
                    <?php foreach ($allCategories as $category): ?>
                        <option value="<?= h($category['id']) ?>" <?= $categoryId === (int) $category['id'] ? 'selected' : '' ?>>
                            <?= h($category['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>
                Search
                <input type="search" name="search" value="<?= h($keyword) ?>" placeholder="Model, SKU, category">
            </label>
            <button class="button" type="submit">Filter</button>
        </form>

        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <article class="product-card">
                    <div class="product-photo">
                        <img src="<?= h(product_image($product)) ?>" alt="<?= h($product['name']) ?>">
                    </div>
                    <div class="product-body">
                        <span class="category"><?= h($product['category_name']) ?> | <?= h($product['sku']) ?></span>
                        <h2>
                            <?php if ($product['sku'] === 'SED-001'): ?>
                                <?= h(preg_replace('/\s+Sedan$/i', '', $product['name'])) ?><br>Sedan
                            <?php else: ?>
                                <?= h($product['name']) ?>
                            <?php endif; ?>
                        </h2>
                        <p><?= h($product['description']) ?></p>
                        <dl class="spec-grid">
                            <?php foreach (vehicle_specs($product) as $label => $value): ?>
                                <div>
                                    <dt><?= h($label) ?></dt>
                                    <dd><?= h($value) ?></dd>
                                </div>
                            <?php endforeach; ?>
                        </dl>
                        <div class="price-row">
                            <strong><?= h(format_peso($product['price'])) ?></strong>
                            <span><?= h($product['stock_quantity']) ?> available</span>
                        </div>
                        <form class="add-cart" method="post" action="<?= h(url_for('store.php')) ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="product_id" value="<?= h($product['id']) ?>">
                            <input type="number" name="quantity" value="1" min="1" max="<?= h($product['stock_quantity']) ?>" aria-label="Quantity">
                            <button class="button small" type="submit">Add to Cart</button>
                        </form>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <?php if (!$products): ?>
            <div class="empty-state">No vehicles match the selected filters.</div>
        <?php endif; ?>
    </section>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
