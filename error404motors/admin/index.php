<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/functions.php';

$admin = require_admin();
$pageTitle = 'Seller Admin';

$stats = [
    'stock' => 0,
    'value' => 0,
    'low_stock' => 0,
    'users' => 0,
];
$recentLogs = [];

try {
    $stats['stock'] = (int) db()->query('SELECT COALESCE(SUM(stock_quantity), 0) FROM products')->fetchColumn();
    $stats['value'] = (float) db()->query('SELECT COALESCE(SUM(stock_quantity * price), 0) FROM products')->fetchColumn();
    $stats['low_stock'] = (int) db()->query('SELECT COUNT(*) FROM products WHERE stock_quantity <= 2')->fetchColumn();
    $stats['users'] = (int) db()->query('SELECT COUNT(*) FROM users')->fetchColumn();
    $recentLogs = db()->query(
        'SELECT a.*, u.complete_name
         FROM audit_logs a
         LEFT JOIN users u ON u.id = a.user_id
         ORDER BY a.created_at DESC
         LIMIT 6'
    )->fetchAll();
} catch (Throwable $error) {
    flash('error', 'Admin dashboard failed to load: ' . $error->getMessage());
}

require_once __DIR__ . '/../includes/header.php';
?>

<section class="page-title admin-title">
    <p class="eyebrow">Seller part</p>
    <h1>Admin Dashboard</h1>
    <p>Manage dealership users, vehicle stocks, prices, inventory, and activity reports.</p>
</section>

<section class="metric-band admin-metrics">
    <div><strong><?= h($stats['stock']) ?></strong><span>remaining items</span></div>
    <div><strong><?= h(format_peso($stats['value'])) ?></strong><span>inventory value</span></div>
    <div><strong><?= h($stats['low_stock']) ?></strong><span>low stock units</span></div>
    <div><strong><?= h($stats['users']) ?></strong><span>system users</span></div>
</section>

<section class="section admin-grid">
    <a class="admin-action" href="<?= h(url_for('admin/users.php')) ?>">
        <strong>Users</strong>
        <span>Add or modify admin and buyer accounts.</span>
    </a>
    <a class="admin-action" href="<?= h(url_for('admin/products.php')) ?>">
        <strong>Stocks</strong>
        <span>Add vehicles, edit prices, and update quantities.</span>
    </a>
    <a class="admin-action" href="<?= h(url_for('admin/reports.php')) ?>">
        <strong>Reports</strong>
        <span>Review remaining inventory and audit logs.</span>
    </a>
</section>

<section class="section">
    <div class="section-heading">
        <p class="eyebrow">Audit trail</p>
        <h2>Recent activity</h2>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentLogs as $log): ?>
                    <tr>
                        <td><?= h($log['created_at']) ?></td>
                        <td><?= h($log['complete_name'] ?? 'System') ?></td>
                        <td><?= h($log['action']) ?></td>
                        <td><?= h(format_audit_details($log['details'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
