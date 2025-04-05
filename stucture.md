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


توضیحات ساختار:
app/Controllers/: کنترلرهایی که درخواست‌ها را پردازش می‌کنند.

IndexController.php: دریافت و پردازش روتر ها

app/Models/: مدل‌ها برای ارتباط با دیتابیس (در صورت نیاز).

User.php: مدیریت کاربران.

app/Services/: کلاس‌هایی که سرویس‌های اصلی را پیاده‌سازی می‌کنند.

routes/: مسیرهای API پروژه.

web.php: برای پردازش درخواست‌های ورودی به روت.

public/: شامل index.php که ورودی اصلی برنامه است.

bootstrap.php: برای مقداردهی اولیه و لود کردن کلاس‌ها.

.env: ذخیره اطلاعات حساس مثل توکن ربات و تنظیمات دیتابیس.

composer.json: برای مدیریت پکیج‌های PHP.