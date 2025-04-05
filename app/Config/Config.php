<?php
namespace App\Config;

class Config
{
    private static $config;

    public static function load()
    {
        if (! self::$config) {
            self::$config = [
                'auto_routing' => $_ENV['AUTO_ROUTING'],
                'log_file'     => $_ENV['LOG_FILE'],
                'db'           => [
                    'driver'    => 'mysql',
                    'host'      => $_ENV['DB_HOST'],
                    'database'  => $_ENV['DB_NAME'],
                    'username'  => $_ENV['DB_USER'],
                    'password'  => $_ENV['DB_PASSWORD'],
                    'charset'   => 'utf8mb4',
                    'collation' => 'utf8mb4_persian_ci',
                    'prefix'    => '',
                ],
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
