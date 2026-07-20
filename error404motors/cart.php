<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $productId = (int) ($_POST['product_id'] ?? 0);
    $quantity = (int) ($_POST['quantity'] ?? 0);
    update_cart_item($productId, $quantity);
    log_activity('Updated cart', 'products', $productId, ['quantity' => $quantity]);
    flash('success', 'Cart updated.');
    redirect('cart.php');
}

$pageTitle = 'Cart';
$cart = ['items' => [], 'subtotal' => 0.0];
$dbError = null;

try {
    $cart = cart_details();
} catch (Throwable $error) {
    $dbError = $error->getMessage();
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="page-title">
    <p class="eyebrow">Buyer part</p>
    <h1>Cart</h1>
    <p>Review selected vehicles before checkout.</p>
</section>

<section class="section narrow">
    <?php if ($dbError): ?>
        <div class="notice-card">
            <h2>Database connection needed</h2>
            <p>Cart details need the products table.</p>
        </div>
    <?php elseif (!$cart['items']): ?>
        <div class="empty-state">
            Your cart is empty.
            <a class="button small" href="<?= h(url_for('store.php')) ?>">Browse Store</a>
        </div>
    <?php else: ?>
        <div class="cart-list">
            <?php foreach ($cart['items'] as $item): ?>
                <article class="cart-item">
                    <div>
                        <span class="category"><?= h($item['category_name']) ?> | <?= h($item['sku']) ?></span>
                        <h2><?= h($item['name']) ?></h2>
                        <p><?= h(format_peso($item['price'])) ?> each</p>
                    </div>
                    <form method="post" action="<?= h(url_for('cart.php')) ?>" class="cart-controls">
                        <?= csrf_field() ?>
                        <input type="hidden" name="product_id" value="<?= h($item['id']) ?>">
                        <input type="number" name="quantity" min="0" max="<?= h($item['stock_quantity']) ?>" value="<?= h($item['cart_quantity']) ?>">
                        <button class="button small secondary" type="submit">Update</button>
                    </form>
                    <strong><?= h(format_peso($item['line_total'])) ?></strong>
                </article>
            <?php endforeach; ?>
        </div>
        <div class="summary-panel">
            <span>Subtotal</span>
            <strong><?= h(format_peso($cart['subtotal'])) ?></strong>
            <a class="button" href="<?= h(url_for('checkout.php')) ?>">Proceed to Checkout</a>
        </div>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

