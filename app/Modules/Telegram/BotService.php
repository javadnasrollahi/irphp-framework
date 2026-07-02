<?php
namespace App\Modules\Telegram;

use App\Services\Logger;

class BotService
{
    public static function getUpdate()
    {
        return json_decode(file_get_contents("php://input"), true);
    }

    public static function logMessage($message)
    {
        Logger::info('telegram.update', is_array($message) ? $message : ['message' => $message]);
    }

    private static function request($method, $data = [])
    {
        $botToken = env('BOT_TOKEN');
        $botUrl   = env('BOT_URL', 'api.telegram.org');
        $url      = "https://{$botUrl}/bot{$botToken}/$method";

        $options = [
            'http' => [
                'header'        => "Content-Type: application/json\r\n",
                'method'        => 'POST',
                'content'       => json_encode($data),
                'ignore_errors' => true,
            ],
        ];

        $context = stream_context_create($options);
        $result  = @file_get_contents($url, false, $context);

        if ($result === false) {
            Logger::error('telegram.request_failed', ['method' => $method]);
        }

        return $result;
    }

    public static function sendMessage($chatId, $text, $replyMarkup = null)
    {
        $data = [
            'chat_id' => $chatId,
            'text'    => $text,
        ];

        if ($replyMarkup) {
            $data['reply_markup'] = json_encode($replyMarkup);
        }

        return self::request('sendMessage', $data);
    }

    public static function sendPhoto($chatId, $photoUrl, $caption = "")
    {
        return self::request('sendPhoto', [
            'chat_id' => $chatId,
            'photo'   => $photoUrl,
            'caption' => $caption,
        ]);
    }

    public static function answerCallbackQuery($callbackQueryId, $text)
    {
        return self::request('answerCallbackQuery', [
            'callback_query_id' => $callbackQueryId,
            'text'              => $text,
            'show_alert'        => false,
        ]);
    }

    /**
     * بررسی عضویت کاربر در یک کانال/گروه تلگرام از طریق getChatMember
     */
    public static function checkUserChannelMembership($userId, string $channelUsername): bool
    {
        $result = self::request('getChatMember', [
            'chat_id' => $channelUsername,
            'user_id' => $userId,
        ]);

        $response = json_decode((string) $result, true);
        $status   = $response['result']['status'] ?? null;

        return in_array($status, ['member', 'administrator', 'creator'], true);
    }
}
