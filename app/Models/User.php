<?php
namespace App\Models;

class User extends BaseModel
{
    protected $table    = 'users'; // نام جدول در دیتابیس
    protected $fillable = [
        'id',
        'user_id',
        'action',
        'poll_options',
        'poll_question',
        'selected_channel',
        'scheduled_time',
    ];                          // فیلدهای قابل پر شدن
    public $timestamps = false; // اگر جدول `created_at` و `updated_at` داره
}
