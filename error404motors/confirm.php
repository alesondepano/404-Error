<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

$token = trim((string) ($_GET['token'] ?? ''));

if ($token === '') {
    flash('error', 'Confirmation token is missing.');
    redirect('login.php');
}

try {
    $stmt = db()->prepare('SELECT id, email FROM users WHERE confirmation_token = ? LIMIT 1');
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if (!$user) {
        flash('error', 'Confirmation token is invalid or already used.');
    } else {
        $stmt = db()->prepare('UPDATE users SET email_verified = 1, confirmation_token = NULL, updated_at = NOW() WHERE id = ?');
        $stmt->execute([$user['id']]);
        log_activity('Confirmed buyer e-mail', 'users', $user['id'], ['email' => $user['email']]);
        flash('success', 'Your e-mail address is confirmed. You can now check out.');
    }
} catch (Throwable $error) {
    flash('error', 'Confirmation failed: ' . $error->getMessage());
}

redirect('login.php');

