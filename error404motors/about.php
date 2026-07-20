<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'About';
$groupMembers = [
    'Sean Bolor - Team Lead',
    'Aleson De Pano - Member',
    'Adrian Cruz - Member',
    'Chris Sabater- Member',
];

require_once __DIR__ . '/includes/header.php';
?>

<section class="page-title">
    <p class="eyebrow"><?= h(COMPANY_NAME) ?></p>
    <h1>About 404 Motors Exchange</h1>
    <p>We built a dealership-style buy-and-sell website for categorized cars, buyer checkout, seller inventory, and activity reports.</p>
</section>

<section class="section about-layout">
    <article>
        <h2>Company profile</h2>
        <p>
            Error 404 is a student-built dealership concept focused on helping buyers browse available vehicles
            while giving sellers a simple admin system for users, stocks, prices, inventory reports, and audit logs.
        </p>
        <p>
            The system applies PHP page includes, user-defined functions, arrays, form validation, sessions,
            MySQL tables, and database-backed CRUD operations.
        </p>
    </article>
    <article>
        <h2>Group members</h2>
        <ul class="member-list">
            <?php foreach ($groupMembers as $member): ?>
                <li><?= h($member) ?></li>
            <?php endforeach; ?>
        </ul>
    </article>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

