<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/functions.php';

$admin = require_admin();
$pageTitle = 'Reports';
$inventory = [];
$auditLogs = [];
$totals = ['items' => 0, 'value' => 0.0];

try {
    $inventory = db()->query(
        'SELECT p.*, c.name AS category_name, (p.stock_quantity * p.price) AS inventory_value
         FROM products p
         JOIN categories c ON c.id = p.category_id
         ORDER BY c.name, p.name'
    )->fetchAll();
    $auditLogs = db()->query(
        'SELECT a.*, u.complete_name, u.email
         FROM audit_logs a
         LEFT JOIN users u ON u.id = a.user_id
         ORDER BY a.created_at DESC
         LIMIT 100'
    )->fetchAll();
    $totals['items'] = (int) db()->query('SELECT COALESCE(SUM(stock_quantity), 0) FROM products')->fetchColumn();
    $totals['value'] = (float) db()->query('SELECT COALESCE(SUM(stock_quantity * price), 0) FROM products')->fetchColumn();
    log_activity('Viewed reports', 'audit_logs', null);
} catch (Throwable $error) {
    flash('error', 'Reports failed to load: ' . $error->getMessage());
}

require_once __DIR__ . '/../includes/header.php';
?>

<section class="page-title admin-title">
    <p class="eyebrow">Seller part</p>
    <h1>Reports</h1>
    <p>Review remaining inventory and system activity.</p>
</section>

<section class="metric-band admin-metrics">
    <div><strong><?= h($totals['items']) ?></strong><span>remaining items</span></div>
    <div><strong><?= h(format_peso($totals['value'])) ?></strong><span>inventory value</span></div>
</section>

<section class="section">
    <div class="section-heading">
        <p class="eyebrow">Inventory report</p>
        <h2>Remaining items</h2>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Vehicle</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Value</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($inventory as $row): ?>
                    <tr>
                        <td><?= h($row['sku']) ?></td>
                        <td><?= h($row['name']) ?></td>
                        <td><?= h($row['category_name']) ?></td>
                        <td><?= h(format_peso($row['price'])) ?></td>
                        <td><?= h($row['stock_quantity']) ?></td>
                        <td><?= h(format_peso($row['inventory_value'])) ?></td>
                        <td><?= h($row['status']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<section class="section">
    <div class="section-heading">
        <p class="eyebrow">Audit log report</p>
        <h2>System activities</h2>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Table</th>
                    <th>Record</th>
                    <th>IP</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($auditLogs as $log): ?>
                    <tr>
                        <td><?= h($log['created_at']) ?></td>
                        <td><?= h($log['complete_name'] ?? $log['email'] ?? 'System') ?></td>
                        <td><?= h($log['action']) ?></td>
                        <td><?= h($log['table_name']) ?></td>
                        <td><?= h($log['record_id']) ?></td>
                        <td><?= h($log['ip_address']) ?></td>
                        <td><?= h(format_audit_details($log['details'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
