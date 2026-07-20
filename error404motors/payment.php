<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

$user = require_login();
$pageTitle = 'Payment';
$checkout = $_SESSION['checkout'] ?? null;
$cart = cart_details();
$paymentMethods = ['Cash on Delivery', 'Bank Transfer', 'Demo E-Wallet', 'Dealer Financing'];
$errors = [];

if (!$checkout || !$cart['items']) {
    flash('error', 'Please complete checkout details first.');
    redirect('checkout.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $paymentMethod = (string) ($_POST['payment_method'] ?? '');
    $reference = trim((string) ($_POST['payment_reference'] ?? ''));

    if (!in_array($paymentMethod, $paymentMethods, true)) {
        $errors[] = 'Select a valid payment method.';
    }

    if ((int) $user['email_verified'] !== 1) {
        $errors[] = 'Please confirm your e-mail address before payment.';
    }

    if (!$errors) {
        $pdo = db();

        try {
            $pdo->beginTransaction();
            $orderNumber = '404-' . date('YmdHis') . '-' . random_int(100, 999);
            $lockedItems = [];
            $total = 0.0;

            foreach ($_SESSION['cart'] as $productId => $quantity) {
                $productId = (int) $productId;
                $quantity = (int) $quantity;
                $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ? AND status = "active" FOR UPDATE');
                $stmt->execute([$productId]);
                $product = $stmt->fetch();

                if (!$product || (int) $product['stock_quantity'] < $quantity) {
                    throw new RuntimeException('One of the selected vehicles no longer has enough stock.');
                }

                $product['cart_quantity'] = $quantity;
                $lockedItems[] = $product;
                $total += $quantity * (float) $product['price'];
            }

            $stmt = $pdo->prepare(
                'INSERT INTO orders
                (buyer_id, order_number, total_amount, payment_method, payment_reference, status, shipping_address, contact_number, created_at)
                VALUES (?, ?, ?, ?, ?, "pending", ?, ?, NOW())'
            );
            $stmt->execute([
                $user['id'],
                $orderNumber,
                $total,
                $paymentMethod,
                $reference,
                $checkout['shipping_address'],
                $checkout['contact_number'],
            ]);
            $orderId = (int) $pdo->lastInsertId();

            $itemStmt = $pdo->prepare(
                'INSERT INTO order_items (order_id, product_id, quantity, unit_price)
                 VALUES (?, ?, ?, ?)'
            );
            $stockStmt = $pdo->prepare('UPDATE products SET stock_quantity = stock_quantity - ?, updated_by = ?, updated_at = NOW() WHERE id = ?');

            foreach ($lockedItems as $item) {
                $itemStmt->execute([$orderId, $item['id'], $item['cart_quantity'], $item['price']]);
                $stockStmt->execute([$item['cart_quantity'], $user['id'], $item['id']]);
            }

            $pdo->commit();
            log_activity('Placed order', 'orders', $orderId, ['order_number' => $orderNumber, 'total' => $total]);
            unset($_SESSION['cart'], $_SESSION['checkout']);
            flash('success', 'Order placed successfully.');
            redirect('order_success.php?order=' . urlencode($orderNumber));
        } catch (Throwable $error) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $errors[] = 'Payment could not be recorded: ' . $error->getMessage();
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="page-title">
    <p class="eyebrow">Buyer part</p>
    <h1>Payment</h1>
    <p>Select a payment method and place the vehicle order.</p>
</section>

<section class="section checkout-layout">
    <form class="panel-form" method="post" action="<?= h(url_for('payment.php')) ?>">
        <?= csrf_field() ?>
        <?php if ($errors): ?>
            <div class="form-errors">
                <?php foreach ($errors as $error): ?>
                    <p><?= h($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <label>
            Payment method
            <select name="payment_method" required>
                <option value="">Select payment method</option>
                <?php foreach ($paymentMethods as $method): ?>
                    <option value="<?= h($method) ?>"><?= h($method) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>
            Payment reference
            <input type="text" name="payment_reference" placeholder="Optional reference number">
        </label>
        <button class="button" type="submit">Place Order</button>
    </form>

    <aside class="summary-panel sticky">
        <span>Total due</span>
        <strong><?= h(format_peso($cart['subtotal'])) ?></strong>
        <p><?= h($checkout['shipping_address']) ?></p>
    </aside>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
