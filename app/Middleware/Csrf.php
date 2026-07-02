<?php
namespace App\Middleware;

class Csrf
{
    public function handle()
    {
        if (in_array($_SERVER['REQUEST_METHOD'], ['GET', 'HEAD', 'OPTIONS'], true)) {
            return;
        }

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $token = $_POST['_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        $valid = ! empty($_SESSION['_csrf_token']) && hash_equals($_SESSION['_csrf_token'], (string) $token);

        if (! $valid) {
            http_response_code(419);
            echo 'CSRF token mismatch.';
            exit;
        }
    }
}
