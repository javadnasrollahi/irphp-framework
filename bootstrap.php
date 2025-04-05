<?php
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use App\Services\Database;

// بارگذاری تنظیمات `.env`
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();


// مقداردهی اولیه دیتابیس
Database::init();