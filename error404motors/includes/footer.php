    </main>
    <footer class="site-footer">
        <div class="footer-brand">
            <img src="<?= h(url_for('assets/logo.png')) ?>" alt="<?= h(APP_NAME) ?> logo">
            <span>Your trusted online car marketplace to buy and sell premium vehicles with confidence.</span>
        </div>
        <div class="footer-links">
            <strong>Quick Links</strong>
            <a href="<?= h(url_for('index.php')) ?>">Home</a>
            <a href="<?= h(url_for('store.php')) ?>">Inventory</a>
            <a href="<?= h(url_for('about.php')) ?>">About</a>
            <a href="<?= h(url_for('about.php')) ?>#contact">Contact</a>
        </div>
        <div class="footer-links" id="contact">
            <strong>Contact Us</strong>
            <span>+63 912 345 6789</span>
            <span>info@error404motors.com</span>
            <span>Quezon City, Philippines</span>
        </div>
    </footer>
    <script src="<?= h(url_for('assets/app.js')) ?>"></script>
</body>
</html>
