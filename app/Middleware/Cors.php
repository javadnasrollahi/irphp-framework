<?php
namespace App\Middleware;

class Cors
{
    public function handle()
    {
        if (! (bool) env('CORS_ENABLED', false)) {
            return;
        }

        $origin  = $_SERVER['HTTP_ORIGIN'] ?? '*';
        $allowed = array_map('trim', explode(',', (string) env('CORS_ALLOWED_ORIGINS', '*')));

        $allowOrigin = in_array('*', $allowed, true) || in_array($origin, $allowed, true)
            ? $origin
            : $allowed[0];

        header("Access-Control-Allow-Origin: {$allowOrigin}");
        header('Access-Control-Allow-Methods: ' . env('CORS_ALLOWED_METHODS', 'GET, POST, PUT, PATCH, DELETE, OPTIONS'));
        header('Access-Control-Allow-Headers: ' . env('CORS_ALLOWED_HEADERS', 'Content-Type, Authorization, X-CSRF-Token'));

        if ((bool) env('CORS_ALLOW_CREDENTIALS', false)) {
            header('Access-Control-Allow-Credentials: true');
        }

        if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
            http_response_code(204);
            exit;
        }
    }
}
