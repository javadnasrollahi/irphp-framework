<?php
namespace App\Middleware;

class Auth
{
    public function handle()
    {
        $header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        $token  = str_starts_with($header, 'Bearer ') ? substr($header, 7) : $header;

        $expected = env('AUTH_TOKEN');

        if (! $expected || ! hash_equals((string) $expected, (string) $token)) {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }
    }
}
