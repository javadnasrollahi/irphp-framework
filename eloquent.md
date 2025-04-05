---

### **1️⃣ مدل ایجاد کردن**
فرض کن یه **جدول `users`** داری. یه مدل براش می‌سازی:  

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users'; // نام جدول در دیتابیس
    protected $fillable = ['name', 'email', 'password']; // فیلدهای قابل پر شدن
    public $timestamps = true; // اگر جدول `created_at` و `updated_at` داره
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

#### 📌 چک کردن **وجود داشتن داده**
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
⚠ **نکته:** باید `fillable` توی مدل **تعریف شده باشه.**

#### 📌 روش دوم (به‌صورت دستی)
```php
$user = new User();
$user->name = 'Ali';
$user->email = 'ali@example.com';
$user->password = password_hash('123456', PASSWORD_BCRYPT);
$user->save();
```

---

### **4️⃣ آپدیت کردن رکورد**
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

### **5️⃣ حذف کردن رکورد**
```php
User::where('email', 'ali@example.com')->delete();
```
یا  
```php
$user = User::find(1);
$user->delete();
```

---

### **6️⃣ استفاده از `join` و `with` برای گرفتن ارتباط‌ها**
#### 📌 اگر `users` و `posts` رابطه **یک به چند** داشته باشند، در مدل `User` اینو اضافه کن:
```php
public function posts()
{
    return $this->hasMany(Post::class, 'user_id');
}
```
#### 📌 گرفتن **همه کاربران همراه با پست‌ها**
```php
$users = User::with('posts')->get();
```
#### 📌 گرفتن کاربری با ID مشخص و همراه با پست‌هایش
```php
$user = User::with('posts')->find(1);
```

---

### **7️⃣ مقداردهی پیش‌فرض (`firstOrCreate`)**
اگه کاربر با ایمیل خاص **وجود داشت**، همونو برمی‌گردونه،  
اگه **وجود نداشت،** جدید می‌سازه:
```php
$user = User::firstOrCreate(
    ['email' => 'ali@example.com'],
    ['name' => 'Ali', 'password' => bcrypt('123456')]
);
```

---

**👨‍💻 اینطوری دیگه نیازی به نوشتن کوئری SQL نداری و همه چیز مرتب و خواناست.**  



برای اینکه چک کنی یه **یوزر با ایمیل خاص** وجود داره یا نه و **اگه بود دیتاشو بگیری،** می‌تونی اینجوری بنویسی:  

---

### **روش 1️⃣ – استفاده از `first`**
```php
$user = User::where('email', 'test@example.com')->first();

if ($user) {
    echo "یوزر پیدا شد: " . $user->name;
} else {
    echo "یوزر وجود ندارد.";
}
```

---

### **روش 2️⃣ – استفاده از `firstOrFail` (برای خطا دادن اگه نباشه)**
```php
try {
    $user = User::where('email', 'test@example.com')->firstOrFail();
    echo "یوزر پیدا شد: " . $user->name;
} catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
    echo "یوزر پیدا نشد!";
}
```

---

### **روش 3️⃣ – استفاده از `firstOrNew` (اگر نباشه، یک مدل جدید ولی ذخیره‌نشده برمی‌گردونه)**
```php
$user = User::firstOrNew(['email' => 'test@example.com']);
if ($user->exists) {
    echo "یوزر پیدا شد: " . $user->name;
} else {
    echo "یوزر وجود ندارد ولی یک آبجکت خالی ایجاد شد.";
}
```

---

### **روش 4️⃣ – استفاده از `exists` فقط برای چک کردن وجود یوزر**
```php
$exists = User::where('email', 'test@example.com')->exists();

if ($exists) {
    echo "یوزر وجود دارد!";
} else {
    echo "یوزر وجود ندارد.";
}
```

---

📌 **نکته:**  
✅ **اگر فقط می‌خوای ببینی وجود داره یا نه، `exists()` سریع‌تره.**  
✅ **اگر می‌خوای هم چک کنی و هم دیتاشو بگیری، `first()` بهتره.**  
