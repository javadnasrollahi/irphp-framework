<?php
namespace App\Services;

class Logger
{
    protected static function write(string $level, string $message, array $context = []): void
    {
        $logFile = storage_path(\App\Config\Config::get('log_file') ?? 'app.log');

        $date = date('Y-m-d H:i:s');
        $line = "[{$date}] {$level}: {$message}";

        if (! empty($context)) {
            $line .= ' ' . json_encode($context, JSON_UNESCAPED_UNICODE);
        }

        @file_put_contents($logFile, $line . PHP_EOL, FILE_APPEND | LOCK_EX);
    }

    public static function info(string $message, array $context = []): void
    {
        self::write('INFO', $message, $context);
    }

    public static function warning(string $message, array $context = []): void
    {
        self::write('WARNING', $message, $context);
    }

    public static function error(string $message, array $context = []): void
    {
        self::write('ERROR', $message, $context);
    }

    public static function debug(string $message, array $context = []): void
    {
        if (app_debug()) {
            self::write('DEBUG', $message, $context);
        }
    }
}
