<?php
namespace App\Helpers;

use DateTime;

class GeneralHelper
{
    /**
     * ✅ اعتبارسنجی فرمت تاریخ و زمان
     */
    public static function validateDateTime($dateTime, $format = 'Y-m-d H:i')
    {
        $dt = DateTime::createFromFormat($format, $dateTime);
        return $dt && $dt->format($format) === $dateTime;
    }

    /**
     * ✅ تولید یک رشته تصادفی
     */
    public static function randomString($length = 10)
    {
        return substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, $length);
    }

    /**
     * ✅ تبدیل متن به اسلاگ (Slug)
     */
    public static function slugify($text)
    {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $text), '-'));
    }
}
