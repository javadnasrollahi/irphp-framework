
# فریم‌ورک IRPHP

**IRPHP** یک فریم‌ورک سبک، قابل توسعه و مدرن برای توسعه وب با PHP است که بر پایه معماری تمیز (Clean Architecture) طراحی شده است.

---

## 🚀 امکانات

- ساختار MVC مرتب با یک DI Container سبک (autowiring از طریق Reflection)
- مسیردهی: GET/POST/PUT/PATCH/DELETE، گروه‌بندی روت، named route، Auto-Routing اختیاری
- Request/Response روان به‌جای دسترسی مستقیم به `$_GET`/`$_POST`
- سیستم View با پشتیبانی از layout و extend
- مدیریت متمرکز خطاها (پیام خوانا در dev، پیام امن در production با `APP_DEBUG`)
- Logger ساده، Validator، میدلورهای CSRF، CORS و Rate Limiting
- دیتابیس با Eloquent؛ به‌صورت پیش‌فرض SQLite بدون نیاز به هیچ پیکربندی
- Migration (`php cli.php migrate`)
- ابزار CLI برای ساخت ماژول (`php cli.php make:module`)
- بارگذاری خودکار کلاس‌ها بر پایه PSR-4 (سازگار با Composer)

---

## 🏗️ ساختار پروژه

```
project/
│── app/
│   ├── Controllers/
│   │   ├── IndexController.php
│   │   ├── .....php
│   ├── Models/
│   │   ├── User.php
│   │   ├── .....php
│   ├── Services/
│   │   ├── ....php
│── routes/
│   ├── web.php
│── public/
│   ├── index.php
│── bootstrap.php
│── .env
│── composer.json
```

---

## ⚙️ نصب

1. ریپوزیتوری را کلون کنید:
   ```bash
   git clone https://github.com/javadnasrollahi/irphp-framework.git
   cd irphp-framework
   ```

2. وابستگی‌ها را نصب کنید:
   ```bash
   composer install
   ```

3. فایل `.env.example` را به `.env` کپی کنید:
   ```bash
   cp .env.example .env
   ```
   تنظیمات پیش‌فرض از **SQLite** استفاده می‌کنند (فایل `storage/database.sqlite` به‌صورت خودکار ساخته می‌شود) — برای امتحان فریم‌ورک نیازی به نصب دیتابیس نیست. برای MySQL کافیه `DB_CONNECTION=mysql` رو در `.env` تنظیم کنی.

---

## 🖥️ اجرای سرور

```bash
composer serve
# یا مستقیم:
php -S 127.0.0.1:8080 -t public
```

سپس مرورگر را باز کرده و وارد آدرس [http://127.0.0.1:8080](http://127.0.0.1:8080) شوید.

برای اجرای migration ها:
```bash
php cli.php migrate
```

---

## 🧩 مسیردهی (Routing)

مسیرها را در `routes/web.php` (یا هر فایل دیگه‌ای داخل `routes/`، چون همه به‌صورت خودکار لود میشن) تعریف کنید:

```php
$router->get('/', 'IndexController@index')->name('home');

$router->group(['prefix' => '/admin', 'middleware' => ['Auth']], function ($router) {
    $router->get('/dashboard', 'AdminController@dashboard');
});

// محدود کردن نرخ درخواست روی یک روت
$router->get('/api/limited', 'IndexController@api', ['RateLimit:30,60']);
```

پشتیبانی از:
- متدهای GET، POST، PUT، PATCH، DELETE
- گروه‌بندی روت (`prefix` + `middleware`) و named route
- Auto-Routing اختیاری (قابل تنظیم با `AUTO_ROUTING=true`)

---

## 🧠 کنترلرها

مثال یک کنترلر:

```php
namespace App\Controllers;

use App\Core\Response;

class IndexController
{
    public function index()
    {
        return Response::make()
            ->view('index', ['name' => 'Amir'])
            ->send();
    }
}
```

---

## 🖼️ ویوها (Views)

فایل ویو: `app/Views/index.php`

```php
<?php \App\Core\View::extend('master'); ?>
<h1>Hello, <?= htmlspecialchars($name) ?> 👋</h1>
```

پشتیبانی از layout و ارسال داده به ویو.

---

## 🧪 تست‌نویسی

برای اجرای تست‌ها از PHPUnit استفاده کنید. ابتدا آن را نصب نمایید:

```bash
composer install --dev
```

برای اجرای تست‌ها:

```bash
composer test
```

تست‌ها در پوشه `tests/` قرار دارند.

---

## 💻 ابزار خط فرمان (CLI)

```bash
php cli.php make:module Category      # کنترلر(ها) + مدل + فایل روت
php cli.php make:migration create_posts_table
php cli.php migrate
php cli.php migrate:rollback
php cli.php serve [host:port]
```

---

## 🧪 برنامه‌های آینده

- [x] پشتیبانی از Middleware
- [x] ابزار خط فرمان (CLI)
- [x] ساختار تست واحد
- [x] مهاجرت دیتابیس (Migration)
- [x] کانتینر تزریق وابستگی (DI Container)
- [x] CORS و Rate Limiting
- [ ] ابزارهای REST API

---

## ❤️ مشارکت در توسعه

هرگونه مشارکت خوش‌آمد است! ریپوزیتوری را fork کرده و Pull Request ارسال کنید.

---

## 📄 لایسنس

MIT © [Javad Nasrollahi](https://github.com/javadnasrollahi)
