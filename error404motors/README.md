## Setup With XAMPP

1. Copy the `404-motors-exchange` folder into your XAMPP `htdocs` folder.
2. Start Apache and MySQL.
3. Open phpMyAdmin and import `sql/schema.sql`.
4. Check `config.php` if your MySQL username or password is different.
5. Open `http://localhost/404-motors-exchange/setup.php`.
6. Log in with:
   - E-mail: `admin@404motors.local`
   - Password: `Admin123!`

- E-mail confirmation token using `mail()` plus a local `storage/mail_log.txt` fallback


