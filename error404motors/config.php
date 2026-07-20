<?php
declare(strict_types=1);

date_default_timezone_set('Asia/Manila');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('APP_NAME', '404 Motors Exchange');
define('COMPANY_NAME', 'Error 404');
define('MAIL_FROM', 'no-reply@404motors.local');

define('DB_HOST', 'localhost');
define('DB_NAME', 'error404_motors');
define('DB_USER', 'root');
define('DB_PASS', '');

