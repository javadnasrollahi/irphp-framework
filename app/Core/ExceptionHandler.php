<?php
namespace App\Core;

use App\Services\Logger;

class ExceptionHandler
{
    public static function register(): void
    {
        set_exception_handler([self::class, 'handleException']);
        set_error_handler([self::class, 'handleError']);
        register_shutdown_function([self::class, 'handleShutdown']);
    }

    public static function handleException(\Throwable $e): void
    {
        Logger::error($e->getMessage(), [
            'file'  => $e->getFile(),
            'line'  => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);

        if (! headers_sent()) {
            http_response_code(500);
        }

        self::render($e);
    }

    public static function handleError(int $level, string $message, string $file = '', int $line = 0): bool
    {
        if (! (error_reporting() & $level)) {
            return false;
        }
        throw new \ErrorException($message, 0, $level, $file, $line);
    }

    public static function handleShutdown(): void
    {
        $error = error_get_last();
        if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) {
            self::handleException(new \ErrorException(
                $error['message'],
                0,
                $error['type'],
                $error['file'],
                $error['line']
            ));
        }
    }

    protected static function render(\Throwable $e): void
    {
        $isJsonRequest = str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json')
        || str_contains($_SERVER['CONTENT_TYPE'] ?? '', 'application/json');

        if ($isJsonRequest) {
            header('Content-Type: application/json');
            echo json_encode(app_debug() ? [
                'error' => $e->getMessage(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
            ] : ['error' => 'Internal Server Error']);
            return;
        }

        if (app_debug()) {
            echo '<pre style="direction:ltr;text-align:left;background:#1e1e1e;color:#f2f2f2;padding:20px;font-family:monospace">';
            echo htmlspecialchars(get_class($e) . ': ' . $e->getMessage()) . "\n";
            echo htmlspecialchars($e->getFile() . ':' . $e->getLine()) . "\n\n";
            echo htmlspecialchars($e->getTraceAsString());
            echo '</pre>';
            return;
        }

        echo '<h1>500 - خطای داخلی سرور</h1>';
    }
}
