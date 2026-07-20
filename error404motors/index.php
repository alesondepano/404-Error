<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Home';
$dbError = null;
$featured = [];
$metrics = ['cars' => 0, 'categories' => 0, 'orders' => 0];

try {
    $featured = featured_products(3);
    $metrics = site_metrics();
} catch (Throwable $error) {
    $dbError = $error->getMessage();
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="hero">
    <div class="hero-copy">
        <p class="eyebrow">Premium online car marketplace</p>
        <h1>Drive <span>Beyond Limits.</span></h1>
        <p class="hero-text">
            Your trusted online car marketplace to buy and sell premium vehicles with confidence.
        </p>
        <div class="hero-actions">
            <a class="button" href="<?= h(url_for('store.php')) ?>">Browse Cars</a>
        </div>
    </div>
</section>

<?php if ($dbError): ?>
    <section class="section narrow">
        <div class="notice-card">
            <h2>Database setup needed</h2>
            <p>Import <strong>sql/schema.sql</strong>, update <strong>config.php</strong> if needed, then open <strong>setup.php</strong> once to seed the admin and sample cars.</p>
        </div>
    </section>
<?php else: ?>
    <section class="section">
        <div class="section-heading centered">
            <span></span>
            <h2>Featured Vehicles</h2>
            <span></span>
        </div>
        <div class="product-grid">
            <?php foreach ($featured as $product): ?>
                <article class="product-card">
                    <div class="product-photo">
                        <img src="<?= h(product_image($product)) ?>" alt="<?= h($product['name']) ?>">
                    </div>
                    <div class="product-body">
                        <span class="category"><?= h($product['category_name']) ?></span>
                        <h3><?= h($product['name']) ?></h3>
                        <p><?= h($product['description']) ?></p>
                        <div class="price-row">
                            <strong><?= h(format_peso($product['price'])) ?></strong>
                            <span><?= h($product['stock_quantity']) ?> in stock</span>
                        </div>
                        <a class="button small full secondary" href="<?= h(url_for('store.php')) ?>">View Details</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="section why-panel">
        <div class="section-heading centered compact">
            <span></span>
            <h2>Why Choose Error 404 Motors?</h2>
            <span></span>
        </div>
        <div class="why-grid">
            <article>
                <strong>Premium Quality Cars</strong>
                <p>All vehicles are inspected and listed with clear stock, price, and specifications.</p>
            </article>
            <article>
                <strong>Affordable Prices</strong>
                <p>Admins can update prices and quantities so buyers always see the current offer.</p>
            </article>
            <article>
                <strong>Secure Transactions</strong>
                <p>Buyer accounts, checkout records, and seller activities are tracked through audit logs.</p>
            </article>
        </div>
    </section>

    <section class="metric-band">
        <div><strong><?= h($metrics['cars']) ?>+</strong><span>Cars Available</span></div>
        <div><strong>800+</strong><span>Happy Customers</span></div>
        <div><strong>50+</strong><span>Trusted Sellers</span></div>
    </section>

    <section class="mission-strip">
        <div>
            <p class="eyebrow">Our Mission</p>
            <p>Error 404 Motors aims to become a trusted online car marketplace by connecting buyers and sellers through a secure, reliable, and user-friendly platform.</p>
        </div>
        <div>
            <h2>Ready to find your dream car?</h2>
            <p>Browse our latest collection today.</p>
            <a class="button secondary" href="<?= h(url_for('store.php')) ?>">Browse Cars</a>
        </div>
    </section>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
