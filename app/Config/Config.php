<?php
namespace App\Config;

class Config
{
    private static $config;

    public static function load()
    {
        if (! self::$config) {
            $driver = env('DB_CONNECTION', 'sqlite');

            $db = $driver === 'sqlite'
                ? [
                'driver'   => 'sqlite',
                'database' => env('DB_DATABASE', storage_path('database.sqlite')),
                'prefix'   => '',
            ]
                : [
                'driver'    => $driver,
                'host'      => env('DB_HOST', '127.0.0.1'),
                'port'      => env('DB_PORT', 3306),
                'database'  => env('DB_NAME', env('DB_DATABASE')),
                'username'  => env('DB_USER', env('DB_USERNAME')),
                'password'  => env('DB_PASSWORD', ''),
                'charset'   => 'utf8mb4',
                'collation' => 'utf8mb4_persian_ci',
                'prefix'    => '',
            ];

            self::$config = [
                'app_debug'    => env('APP_DEBUG', false),
                'app_url'      => env('APP_URL', 'http://127.0.0.1:8080'),
                'auto_routing' => env('AUTO_ROUTING', false),
                'log_file'     => env('LOG_FILE', 'app.log'),
                'auth_token'   => env('AUTH_TOKEN'),
                'db'           => $db,
            ];
        }
    }

    public static function get($key)
    {
        self::load();
        return self::$config[$key] ?? null;
    }

    public static function getDbConfig()
    {
        self::load();
        return self::$config['db'] ?? [];
    }
}
