<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

if (current_user()) {
    redirect('index.php');
}

$pageTitle = 'Login';
$errors = [];
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $email = trim((string) ($_POST['email'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
        $errors[] = 'Enter a valid e-mail and password.';
    } else {
        try {
            $stmt = db()->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if (!$user || !password_verify($password, $user['password_hash'])) {
                $errors[] = 'Invalid e-mail or password.';
            } elseif ($user['status'] !== 'active') {
                $errors[] = 'This account is disabled.';
            } else {
                session_regenerate_id(true);
                $_SESSION['user_id'] = (int) $user['id'];
                log_activity('Logged in', 'users', $user['id'], ['role' => $user['role']]);
                redirect($user['role'] === 'admin' ? 'admin/index.php' : 'index.php');
            }
        } catch (Throwable $error) {
            $errors[] = 'Login failed: ' . $error->getMessage();
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="page-title">
    <p class="eyebrow">Account</p>
    <h1>Login</h1>
    <p>Access buyer checkout or the seller admin area.</p>
</section>

<section class="section narrow">
    <?php if ($errors): ?>
        <div class="form-errors">
            <?php foreach ($errors as $error): ?>
                <p><?= h($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form class="panel-form" method="post" action="<?= h(url_for('login.php')) ?>">
        <?= csrf_field() ?>
        <label>
            E-mail address
            <input type="email" name="email" value="<?= h($email) ?>" required>
        </label>
        <label>
            Password
            <input type="password" name="password" required>
        </label>
        <button class="button" type="submit">Login</button>
    </form>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

