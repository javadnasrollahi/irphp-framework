<?php
namespace Core\Middleware;

class Auth
{
    public function handle()
    {
        if (! isset($_SERVER['HTTP_AUTH']) || $_SERVER['HTTP_AUTH'] !== 'your-secret') {
            http_response_code(401);
            die('Unauthorized');
        }
    }
}
