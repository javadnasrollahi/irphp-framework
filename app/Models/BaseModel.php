<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    // مدل‌های فرزند باید $fillable رو خودشون تعریف کنن، پیش‌فرض هیچی fillable نیست
    protected $guarded = ['id'];
    public $timestamps = false; // اگر جدول `created_at` و `updated_at` داره
}
