<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    protected $guarded = [];    // غیرفعال کردن محافظت از فیلدها
    public $timestamps = false; // اگر جدول `created_at` و `updated_at` داره
}
