<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

$user = require_login();
$pageTitle = 'Checkout';
$cart = cart_details();

if (!$cart['items']) {
    flash('error', 'Your cart is empty.');
    redirect('cart.php');
}

$errors = [];
$values = [
    'shipping_address' => $user['complete_address'],
    'contact_number' => $user['contact_numbers'],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $values = [
        'shipping_address' => trim((string) ($_POST['shipping_address'] ?? '')),
        'contact_number' => trim((string) ($_POST['contact_number'] ?? '')),
    ];

    if ((int) $user['email_verified'] !== 1) {
        $errors[] = 'Please confirm your e-mail address before checkout.';
    }

    if ($values['shipping_address'] === '') {
        $errors[] = 'Shipping address is required.';
    }

    if (!valid_contact_number($values['contact_number'])) {
        $errors[] = 'A valid contact number is required.';
    }

    if (!$errors) {
        $_SESSION['checkout'] = $values;
        log_activity('Prepared checkout', 'orders', null, ['subtotal' => $cart['subtotal']]);
        redirect('payment.php');
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="page-title">
    <p class="eyebrow">Buyer part</p>
    <h1>Checkout</h1>
    <p>Confirm delivery details before payment.</p>
</section>

<section class="section checkout-layout">
    <form class="panel-form" method="post" action="<?= h(url_for('checkout.php')) ?>">
        <?= csrf_field() ?>
        <?php if ((int) $user['email_verified'] !== 1): ?>
            <div class="form-errors">
                <p>Your e-mail address is not yet confirmed.</p>
            </div>
        <?php endif; ?>
        <?php if ($errors): ?>
            <div class="form-errors">
                <?php foreach ($errors as $error): ?>
                    <p><?= h($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <label>
            Complete address
            <textarea name="shipping_address" rows="5" required><?= h($values['shipping_address']) ?></textarea>
        </label>
        <label>
            Contact numbers
            <input type="text" name="contact_number" value="<?= h($values['contact_number']) ?>" required>
        </label>
        <button class="button" type="submit">Continue to Payment</button>
    </form>

    <aside class="summary-panel sticky">
        <span>Order subtotal</span>
        <strong><?= h(format_peso($cart['subtotal'])) ?></strong>
        <p><?= h(count($cart['items'])) ?> vehicle type(s) selected</p>
    </aside>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

