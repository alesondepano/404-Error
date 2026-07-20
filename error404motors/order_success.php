<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

$user = require_login();
$pageTitle = 'Order Complete';
$orderNumber = trim((string) ($_GET['order'] ?? ''));
$order = null;

if ($orderNumber !== '') {
    $stmt = db()->prepare('SELECT * FROM orders WHERE order_number = ? AND buyer_id = ? LIMIT 1');
    $stmt->execute([$orderNumber, $user['id']]);
    $order = $stmt->fetch();
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="section narrow">
    <div class="notice-card success">
        <p class="eyebrow">Order recorded</p>
        <h1 class="order-success-number"><?= $order ? h($order['order_number']) : 'Order complete' ?></h1>
        <p>Your vehicle order is now saved for seller review.</p>
        <a class="button" href="<?= h(url_for('store.php')) ?>">Continue Shopping</a>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
