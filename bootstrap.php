<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Core\ExceptionHandler;
use App\Services\Database;
use Dotenv\Dotenv;

// بارگذاری تنظیمات `.env` (اگه فایل .env نبود، از env واقعی سرور استفاده میشه)
if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

// مدیریت متمرکز خطاها و استثناها
ExceptionHandler::register();

// سشن برای CSRF و امکانات مشابه
if (php_sapi_name() !== 'cli' && session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// مقداردهی اولیه دیتابیس (اتصال lazy است، تا وقتی کوئری نزنی واقعاً connect نمیشه)
Database::init();
