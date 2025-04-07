
# فریم‌ورک IRPHP

**IRPHP** یک فریم‌ورک سبک، قابل توسعه و مدرن برای توسعه وب با PHP است که بر پایه معماری تمیز (Clean Architecture) طراحی شده است.

---

## 🚀 امکانات

- ساختار MVC مرتب و ساده
- سیستم مسیردهی ساده و قابل تنظیم (با قابلیت Auto-Routing اختیاری)
- سیستم پاسخ‌دهی HTTP روان و قدرتمند
- سیستم View با پشتیبانی از layout و extend
- پشتیبانی از فایل `.env`
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

3. فایل `.env.example` را به `.env` کپی کرده و تنظیمات محیطی را انجام دهید.

---

## 🖥️ اجرای سرور

برای اجرای سرور داخلی PHP از دستور زیر استفاده کنید:

```bash
php -S 127.0.0.1:8080 -t public
```

سپس مرورگر را باز کرده و وارد آدرس [http://127.0.0.1:8080](http://127.0.0.1:8080) شوید.

---

## 🧩 مسیردهی (Routing)

مسیرها را در فایل `routes/webhook.php` تعریف کنید:

```php
$router->get('/', 'IndexController@index');
```

پشتیبانی از:
- متدهای GET، POST، PUT، DELETE
- Auto-Routing اختیاری (قابل تنظیم)

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

با استفاده از CLI می‌توانید به‌صورت خودکار ماژول‌ها، کنترلرها و سایر کامپوننت‌ها را بسازید.

برای ساخت یک ماژول جدید (مثلاً category):

```bash
composer cli category
```

این دستور فایل‌های زیر را ایجاد می‌کند:

- کنترلرها: `items.php`, `item.php`, `create.php`, `update.php`, `delete.php`
- فایل مدل
- فایل روت

این قابلیت به شما در توسعه سریع‌تر کمک می‌کند.

---

## 🧪 برنامه‌های آینده

- [x] پشتیبانی از Middleware  
- [x] ابزار خط فرمان (CLI)  
- [x] ساختار تست واحد  
- [ ] مهاجرت دیتابیس (Migration)  
- [ ] کانتینر تزریق وابستگی (DI Container)  
- [ ] ابزارهای REST API  

---

## ❤️ مشارکت در توسعه

هرگونه مشارکت خوش‌آمد است! ریپوزیتوری را fork کرده و Pull Request ارسال کنید.

---

## 📄 لایسنس

MIT © [Javad Nasrollahi](https://github.com/javadnasrollahi)
