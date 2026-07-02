<?php
namespace App\Services;

use App\Config\Config;
use Illuminate\Database\Capsule\Manager as Capsule;

class Database
{
    public static function init()
    {
        $dbConfig = Config::get('db');

        if (($dbConfig['driver'] ?? null) === 'sqlite' && ! file_exists($dbConfig['database'])) {
            @mkdir(dirname($dbConfig['database']), 0777, true);
            touch($dbConfig['database']);
        }

        $capsule = new Capsule;

        $capsule->addConnection($dbConfig);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }
}
