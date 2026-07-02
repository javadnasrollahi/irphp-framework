<?php
namespace App\Modules\Telegram;

class AccessChecker
{
    protected $user;
    protected $chatId;

    public function __construct($chatId, $user)
    {
        $this->chatId = $chatId;
        $this->user   = $user;
    }

    /**
     * بررسی کانال ثبت‌شده کاربر
     */
    public function checkUserHashChannels(): bool
    {
        if (count($this->user['channels_list'] ?? []) === 0) {
            BotService::sendMessage($this->chatId, "⚠️ شما هیچ کانالی متصل نکرده‌اید.\nلطفاً یک کانال متصل کنید");
            return false;
        }
        return true;
    }

    public function checkUserIsAdmin(): bool
    {
        if (empty($this->user['isAdmin'])) {
            BotService::sendMessage($this->chatId, "⚠️ شما مدیر نیستید!");
            return false;
        }
        return true;
    }

    /**
     * بررسی تاریخ انقضای اشتراک کاربر
     */
    public function checkUserSubscription(): bool
    {
        if (! empty($this->user['expiry_date']) && strtotime($this->user['expiry_date']) < time()) {
            BotService::sendMessage($this->chatId, "⛔ اشتراک شما به پایان رسیده است. لطفاً اشتراک خود را تمدید کنید.");
            return false;
        }
        return true;
    }

    /**
     * بررسی عضویت در کانال تلگرام
     */
    public function checkChannelSubscription(string $channelUsername = null): bool
    {
        $channelUsername = $channelUsername ?? env('REQUIRED_CHANNEL', '@YourChannel');
        $isMember        = BotService::checkUserChannelMembership($this->chatId, $channelUsername);

        if (! $isMember) {
            BotService::sendMessage($this->chatId, "⛔ لطفاً ابتدا در کانال ما عضو شوید: " . $channelUsername);
            return false;
        }
        return true;
    }

    /**
     * اجرای همه‌ی چک‌ها
     */
    public function runChecks(): bool
    {
        if (! $this->checkUserSubscription()) {
            return false;
        }
        if (! $this->checkChannelSubscription()) {
            return false;
        }
        return true;
    }
}
