<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/functions.php';

$admin = require_admin();
$pageTitle = 'Manage Users';
$users = [];

try {
    $users = db()->query(
        'SELECT id, complete_name, email, role, email_verified, status, created_at
         FROM users
         ORDER BY role, complete_name'
    )->fetchAll();
} catch (Throwable $error) {
    flash('error', 'Users failed to load: ' . $error->getMessage());
}

require_once __DIR__ . '/../includes/header.php';
?>

<section class="page-title admin-title">
    <p class="eyebrow">Seller part</p>
    <h1>System Users</h1>
    <p>Add or modify accounts that can access buyer or admin roles.</p>
    <a class="button" href="<?= h(url_for('admin/user_form.php')) ?>">Add User</a>
</section>

<section class="section">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>E-mail</th>
                    <th>Role</th>
                    <th>Verified</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $row): ?>
                    <tr>
                        <td><?= h($row['complete_name']) ?></td>
                        <td><?= h($row['email']) ?></td>
                        <td><span class="badge"><?= h($row['role']) ?></span></td>
                        <td><?= (int) $row['email_verified'] === 1 ? 'Yes' : 'No' ?></td>
                        <td><?= h($row['status']) ?></td>
                        <td><?= h($row['created_at']) ?></td>
                        <td><a class="button small secondary" href="<?= h(url_for('admin/user_form.php?id=' . $row['id'])) ?>">Edit</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

