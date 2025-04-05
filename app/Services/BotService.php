<?php
namespace App\Services;
use App\Config\Config;

class BotService
{

    public static function getUpdate()
    {
        return json_decode(file_get_contents("php://input"), true);
    }

    public static function logMessage($message)
    {
        $log_file = Config::get('log_file');
        $logFile = __DIR__ . "/../../storage/" . $log_file;
        
        $date = date('Y-m-d H:i:s');
        $logMessage = "[{$date}] " . print_r($message, true) . "\n";
        
        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }

    private static function request($method, $data = [])
    {
        $botToken = Config::get('bot_token');
        $botUrl = Config::get('bot_url');
        $url = "https://{$botUrl}/bot{$botToken}/$method";

        $options = [
            'http' => [
                'header'  => "Content-Type: application/json\r\n",
                'method'  => 'POST',
                'content' => json_encode($data),
            ],
        ];

        $context  = stream_context_create($options);
        return file_get_contents($url, false, $context);
    }

    public static function sendMessage($chatId, $text, $replyMarkup = null)
    {
        $data = [
            'chat_id' => $chatId,
            'text' => $text,
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
            'photo' => $photoUrl,
            'caption' => $caption,
        ]);
    }

    public static function answerCallbackQuery($callbackQueryId, $text)
    {
        return self::request('answerCallbackQuery', [
            'callback_query_id' => $callbackQueryId,
            'text' => $text,
            'show_alert' => false // اگر `true` باشد، پیام به‌صورت هشدار نمایش داده می‌شود.
        ]);
    }
}
