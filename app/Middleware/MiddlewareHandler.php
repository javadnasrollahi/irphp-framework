<?php
namespace App\Middleware;

use App\Services\BotService;

class MiddlewareHandler
{
    protected $user;
    protected $chatId;

    public function __construct($chatId, $user)
    {
        $this->chatId = $chatId;
        $this->user = $user;
    }

    /**
     * ✅ بررسی کانال ثبت کردن کاربر
     */
    public function checkUserHashChannels()
    {
        if (count($this->user["channels_list"]) == 0) {
            BotService::sendMessage($this->chatId,  "⚠️ شما هیچ کانالی متصل نکرده‌اید.\nلطفاً یک کانال متصل کنید");
            return false;
        }
        return true;
    }
    public function checkUserIsAdmin()
    {
        if (!$this->user["isAdmin"]) {
            BotService::sendMessage($this->chatId,  "⚠️ شما مدیر نیستید!");
            return false;
        }
        return true;
    }
    
    /**
     * ✅ بررسی تاریخ انقضای کاربر
     */
    public function checkUserSubscription()
    {
        // if ($this->user->expiry_date < now()) {
        //     BotService::sendMessage($this->chatId, "⛔ اشتراک شما به پایان رسیده است. لطفاً اشتراک خود را تمدید کنید.");
        //     return false;
        // }
        return true;
    }

    /**
     * ✅ بررسی عضویت در کانال تلگرام
     */
    public function checkChannelSubscription()
    {
        $channelUsername = "@YourChannel"; // نام کاربری کانال شما
        $isMember = BotService::checkUserChannelMembership($this->chatId, $channelUsername);

        if (!$isMember) {
            BotService::sendMessage($this->chatId, "⛔ لطفاً ابتدا در کانال ما عضو شوید: " . $channelUsername);
            return false;
        }
        return true;
    }

    /**
     * ✅ اجرای همه‌ی چک‌ها
     */
    public function runChecks()
    {
        if (!$this->checkUserSubscription()) return false;
        if (!$this->checkChannelSubscription()) return false;
        return true;
    }
}
