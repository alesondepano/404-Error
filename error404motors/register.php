<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Register';
$errors = [];
$values = [
    'complete_name' => '',
    'email' => '',
    'complete_address' => '',
    'contact_numbers' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $values = [
        'complete_name' => trim((string) ($_POST['complete_name'] ?? '')),
        'email' => trim((string) ($_POST['email'] ?? '')),
        'complete_address' => trim((string) ($_POST['complete_address'] ?? '')),
        'contact_numbers' => trim((string) ($_POST['contact_numbers'] ?? '')),
    ];
    $password = (string) ($_POST['password'] ?? '');
    $confirmPassword = (string) ($_POST['confirm_password'] ?? '');

    if ($values['complete_name'] === '') {
        $errors[] = 'Complete name is required.';
    }

    if (!filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'A valid e-mail address is required.';
    }

    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }

    if ($password !== $confirmPassword) {
        $errors[] = 'Password and confirm password must match.';
    }

    if ($values['complete_address'] === '') {
        $errors[] = 'Complete address is required.';
    }

    if (!valid_contact_number($values['contact_numbers'])) {
        $errors[] = 'Contact number must contain 7 to 30 valid phone characters.';
    }

    if (!$errors) {
        try {
            $stmt = db()->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
            $stmt->execute([$values['email']]);

            if ($stmt->fetch()) {
                $errors[] = 'That e-mail address is already registered.';
            } else {
                $token = bin2hex(random_bytes(32));
                $stmt = db()->prepare(
                    'INSERT INTO users
                    (complete_name, email, password_hash, complete_address, contact_numbers, role, email_verified, confirmation_token, status, created_at, updated_at)
                    VALUES (?, ?, ?, ?, ?, "buyer", 0, ?, "active", NOW(), NOW())'
                );
                $stmt->execute([
                    $values['complete_name'],
                    $values['email'],
                    password_hash($password, PASSWORD_DEFAULT),
                    $values['complete_address'],
                    $values['contact_numbers'],
                    $token,
                ]);
                $userId = (int) db()->lastInsertId();
                $sent = send_confirmation_email($values['email'], $values['complete_name'], $token);
                log_activity('Registered buyer account', 'users', $userId, ['email' => $values['email']]);

                $mailNote = $sent
                    ? 'A confirmation e-mail was sent.'
                    : 'A confirmation copy was saved to storage/mail_log.txt for local testing.';
                flash('success', 'Registration successful. ' . $mailNote);
                redirect('login.php');
            }
        } catch (Throwable $error) {
            $errors[] = 'Registration failed: ' . $error->getMessage();
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="page-title">
    <p class="eyebrow">Buyer part</p>
    <h1>Register</h1>
    <p>Create a buyer account and confirm your e-mail address.</p>
</section>

<section class="section narrow">
    <?php if ($errors): ?>
        <div class="form-errors">
            <?php foreach ($errors as $error): ?>
                <p><?= h($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form class="panel-form" method="post" action="<?= h(url_for('register.php')) ?>">
        <?= csrf_field() ?>
        <label>
            Complete name
            <input type="text" name="complete_name" value="<?= h($values['complete_name']) ?>" required>
        </label>
        <label>
            E-mail address
            <input type="email" name="email" value="<?= h($values['email']) ?>" required>
        </label>
        <div class="form-grid">
            <label>
                Password
                <input type="password" name="password" minlength="8" required>
            </label>
            <label>
                Confirm password
                <input type="password" name="confirm_password" minlength="8" required>
            </label>
        </div>
        <label>
            Complete address
            <textarea name="complete_address" rows="4" required><?= h($values['complete_address']) ?></textarea>
        </label>
        <label>
            Contact numbers
            <input type="text" name="contact_numbers" value="<?= h($values['contact_numbers']) ?>" required>
        </label>
        <button class="button" type="submit">Create Account</button>
    </form>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

