<?php
$user = current_user();
$currentPage = basename($_SERVER['PHP_SELF']);
$stylesPath = __DIR__ . '/../assets/styles.css';
$stylesVersion = is_file($stylesPath) ? (string) filemtime($stylesPath) : '1';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= h($pageTitle ?? APP_NAME) ?> | <?= h(APP_NAME) ?></title>
    <link rel="stylesheet" href="<?= h(url_for('assets/styles.css') . '?v=' . $stylesVersion) ?>">
</head>
<body>
<header class="site-header">
    <a class="brand" href="<?= h(url_for('index.php')) ?>" aria-label="<?= h(APP_NAME) ?>">
        <img class="brand-logo" src="<?= h(url_for('assets/logo.png')) ?>" alt="<?= h(APP_NAME) ?> logo">
    </a>
    <button class="menu-toggle" type="button" data-menu-toggle aria-label="Open navigation">Menu</button>
    <nav class="main-nav" data-main-nav>
        <a class="<?= $currentPage === 'index.php' ? 'active' : '' ?>" href="<?= h(url_for('index.php')) ?>">Home</a>
        <a class="<?= $currentPage === 'store.php' ? 'active' : '' ?>" href="<?= h(url_for('store.php')) ?>">Store</a>
        <a class="<?= $currentPage === 'about.php' ? 'active' : '' ?>" href="<?= h(url_for('about.php')) ?>">About</a>
        <a class="<?= $currentPage === 'cart.php' ? 'active' : '' ?>" href="<?= h(url_for('cart.php')) ?>">Cart <span class="pill"><?= cart_count() ?></span></a>
        <?php if ($user && $user['role'] === 'admin'): ?>
            <a href="<?= h(url_for('admin/index.php')) ?>">Seller Admin</a>
        <?php endif; ?>
        <?php if ($user): ?>
            <span class="nav-user"><?= h($user['complete_name']) ?></span>
            <a href="<?= h(url_for('logout.php')) ?>">Logout</a>
        <?php else: ?>
            <a class="nav-button outline <?= $currentPage === 'login.php' ? 'active' : '' ?>" href="<?= h(url_for('login.php')) ?>">Login</a>
            <a class="button small" href="<?= h(url_for('register.php')) ?>">Register</a>
        <?php endif; ?>
    </nav>
</header>

<main>
    <?php foreach (consume_flash() as $flash): ?>
        <div class="flash <?= h($flash['type']) ?>"><?= h($flash['message']) ?></div>
    <?php endforeach; ?>
