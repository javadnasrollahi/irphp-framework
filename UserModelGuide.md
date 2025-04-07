# راهنمای کامل کار با مدل User در IRPHP (بر اساس Eloquent)

---

### **1️⃣ ایجاد مدل**
فرض کنیم یک **جدول `users`** داریم. برای آن یک مدل به شکل زیر ایجاد می‌کنیم:

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users'; // نام جدول در دیتابیس
    protected $fillable = ['name', 'email', 'password']; // فیلدهای قابل پر شدن
    public $timestamps = true; // اگر جدول `created_at` و `updated_at` دارد
}
```

---

### **2️⃣ گرفتن اطلاعات**

#### 📌 گرفتن **همه رکوردها**
```php
$users = User::all();
```

#### 📌 گرفتن یک رکورد با `id`
```php
$user = User::find(1);
```

#### 📌 گرفتن رکورد با **شرط خاص**
```php
$user = User::where('email', 'test@example.com')->first();
```

#### 📌 چک کردن **وجود داده**
```php
$exists = User::where('email', 'test@example.com')->exists();
if ($exists) {
    echo "یوزر موجوده!";
}
```

---

### **3️⃣ اضافه کردن رکورد**

#### 📌 روش اول (متد `create`)
```php
User::create([
    'name' => 'Ali',
    'email' => 'ali@example.com',
    'password' => password_hash('123456', PASSWORD_BCRYPT),
]);
```
⚠ **نکته:** باید `fillable` در مدل تعریف شده باشد.

#### 📌 روش دوم (دستی)
```php
$user = new User();
$user->name = 'Ali';
$user->email = 'ali@example.com';
$user->password = password_hash('123456', PASSWORD_BCRYPT);
$user->save();
```

---

### **4️⃣ آپدیت رکورد**

#### 📌 روش اول (متد `update`)
```php
User::where('email', 'ali@example.com')->update([
    'name' => 'Ali Reza',
]);
```

#### 📌 روش دوم (روی یک مدل خاص)
```php
$user = User::find(1);
$user->name = 'Ali Reza';
$user->save();
```

---

### **5️⃣ حذف رکورد**
```php
User::where('email', 'ali@example.com')->delete();
```
یا:
```php
$user = User::find(1);
$user->delete();
```

---

### **6️⃣ استفاده از `join` و `with` برای روابط**

#### 📌 رابطه یک به چند بین `users` و `posts`
در مدل `User`:
```php
public function posts()
{
    return $this->hasMany(Post::class, 'user_id');
}
```

#### 📌 گرفتن همه کاربران با پست‌هایشان
```php
$users = User::with('posts')->get();
```

#### 📌 گرفتن کاربر خاص با پست‌هایش
```php
$user = User::with('posts')->find(1);
```

---

### **7️⃣ مقداردهی پیش‌فرض (`firstOrCreate`)**
```php
$user = User::firstOrCreate(
    ['email' => 'ali@example.com'],
    ['name' => 'Ali', 'password' => bcrypt('123456')]
);
```

---

### **چک کردن وجود کاربر با ایمیل خاص**

#### ✅ **روش 1 – `first`**
```php
$user = User::where('email', 'test@example.com')->first();

if ($user) {
    echo "یوزر پیدا شد: " . $user->name;
} else {
    echo "یوزر وجود ندارد.";
}
```

#### ✅ **روش 2 – `firstOrFail`**
```php
try {
    $user = User::where('email', 'test@example.com')->firstOrFail();
    echo "یوزر پیدا شد: " . $user->name;
} catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
    echo "یوزر پیدا نشد!";
}
```

#### ✅ **روش 3 – `firstOrNew`**
```php
$user = User::firstOrNew(['email' => 'test@example.com']);
if ($user->exists) {
    echo "یوزر پیدا شد: " . $user->name;
} else {
    echo "یوزر وجود ندارد ولی یک آبجکت خالی ایجاد شد.";
}
```

#### ✅ **روش 4 – `exists`**
```php
$exists = User::where('email', 'test@example.com')->exists();

if ($exists) {
    echo "یوزر وجود دارد!";
} else {
    echo "یوزر وجود ندارد.";
}
```

---

📌 **نکات نهایی:**
- ✅ اگر فقط می‌خواهید وجود داشتن رکورد را بررسی کنید، `exists()` سریع‌تر و بهینه‌تر است.
- ✅ اگر نیاز به اطلاعات رکورد دارید، از `first()` استفاده کنید.

---

**👨‍💻 با این متدها، مدیریت اطلاعات کاربران ساده، خوانا و بدون نیاز به SQL خام خواهد بود.**

