<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/functions.php';

$admin = require_admin();
$pageTitle = 'User Form';
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$editing = $id > 0;
$errors = [];
$values = [
    'complete_name' => '',
    'email' => '',
    'complete_address' => '',
    'contact_numbers' => '',
    'role' => 'buyer',
    'status' => 'active',
    'email_verified' => 1,
];

if ($editing) {
    $stmt = db()->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
    $stmt->execute([$id]);
    $found = $stmt->fetch();

    if (!$found) {
        flash('error', 'User not found.');
        redirect('admin/users.php');
    }

    $values = array_merge($values, $found);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $values = [
        'complete_name' => trim((string) ($_POST['complete_name'] ?? '')),
        'email' => trim((string) ($_POST['email'] ?? '')),
        'complete_address' => trim((string) ($_POST['complete_address'] ?? '')),
        'contact_numbers' => trim((string) ($_POST['contact_numbers'] ?? '')),
        'role' => (string) ($_POST['role'] ?? 'buyer'),
        'status' => (string) ($_POST['status'] ?? 'active'),
        'email_verified' => isset($_POST['email_verified']) ? 1 : 0,
    ];
    $password = (string) ($_POST['password'] ?? '');

    if ($values['complete_name'] === '') {
        $errors[] = 'Complete name is required.';
    }

    if (!filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Valid e-mail is required.';
    }

    if (!$editing && strlen($password) < 8) {
        $errors[] = 'Password is required and must be at least 8 characters.';
    }

    if ($password !== '' && strlen($password) < 8) {
        $errors[] = 'New password must be at least 8 characters.';
    }

    if ($values['complete_address'] === '') {
        $errors[] = 'Complete address is required.';
    }

    if (!valid_contact_number($values['contact_numbers'])) {
        $errors[] = 'Valid contact number is required.';
    }

    if (!in_array($values['role'], ['buyer', 'admin'], true)) {
        $errors[] = 'Role is invalid.';
    }

    if (!in_array($values['status'], ['active', 'disabled'], true)) {
        $errors[] = 'Status is invalid.';
    }

    if (!$errors) {
        $stmt = db()->prepare('SELECT id FROM users WHERE email = ? AND id <> ? LIMIT 1');
        $stmt->execute([$values['email'], $id]);

        if ($stmt->fetch()) {
            $errors[] = 'That e-mail is already used by another account.';
        }
    }

    if (!$errors) {
        if ($editing) {
            $params = [
                $values['complete_name'],
                $values['email'],
                $values['complete_address'],
                $values['contact_numbers'],
                $values['role'],
                $values['email_verified'],
                $values['status'],
            ];
            $passwordSql = '';

            if ($password !== '') {
                $passwordSql = ', password_hash = ?';
                $params[] = password_hash($password, PASSWORD_DEFAULT);
            }

            $params[] = $id;
            $stmt = db()->prepare(
                'UPDATE users
                 SET complete_name = ?, email = ?, complete_address = ?, contact_numbers = ?,
                     role = ?, email_verified = ?, status = ?, updated_at = NOW()' . $passwordSql . '
                 WHERE id = ?'
            );
            $stmt->execute($params);
            log_activity('Updated user', 'users', $id, ['email' => $values['email'], 'role' => $values['role']]);
            flash('success', 'User updated.');
        } else {
            $stmt = db()->prepare(
                'INSERT INTO users
                (complete_name, email, password_hash, complete_address, contact_numbers, role, email_verified, confirmation_token, status, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, NULL, ?, NOW(), NOW())'
            );
            $stmt->execute([
                $values['complete_name'],
                $values['email'],
                password_hash($password, PASSWORD_DEFAULT),
                $values['complete_address'],
                $values['contact_numbers'],
                $values['role'],
                $values['email_verified'],
                $values['status'],
            ]);
            $newId = (int) db()->lastInsertId();
            log_activity('Created user', 'users', $newId, ['email' => $values['email'], 'role' => $values['role']]);
            flash('success', 'User created.');
        }

        redirect('admin/users.php');
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<section class="page-title admin-title">
    <p class="eyebrow">Seller part</p>
    <h1><?= $editing ? 'Edit User' : 'Add User' ?></h1>
</section>

<section class="section narrow">
    <?php if ($errors): ?>
        <div class="form-errors">
            <?php foreach ($errors as $error): ?>
                <p><?= h($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form class="panel-form" method="post" action="<?= h(url_for('admin/user_form.php' . ($editing ? '?id=' . $id : ''))) ?>">
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
                Role
                <select name="role">
                    <option value="buyer" <?= $values['role'] === 'buyer' ? 'selected' : '' ?>>Buyer</option>
                    <option value="admin" <?= $values['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                </select>
            </label>
            <label>
                Status
                <select name="status">
                    <option value="active" <?= $values['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="disabled" <?= $values['status'] === 'disabled' ? 'selected' : '' ?>>Disabled</option>
                </select>
            </label>
        </div>
        <label>
            Password <?= $editing ? '<span class="muted">(leave blank to keep current)</span>' : '' ?>
            <input type="password" name="password" minlength="8" <?= $editing ? '' : 'required' ?>>
        </label>
        <label>
            Complete address
            <textarea name="complete_address" rows="4" required><?= h($values['complete_address']) ?></textarea>
        </label>
        <label>
            Contact numbers
            <input type="text" name="contact_numbers" value="<?= h($values['contact_numbers']) ?>" required>
        </label>
        <label class="check-row">
            <input type="checkbox" name="email_verified" <?= (int) $values['email_verified'] === 1 ? 'checked' : '' ?>>
            E-mail verified
        </label>
        <div class="form-actions">
            <button class="button" type="submit">Save User</button>
            <a class="button secondary" href="<?= h(url_for('admin/users.php')) ?>">Cancel</a>
        </div>
    </form>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

