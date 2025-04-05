<?php

namespace App\Services;

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Config\Config;

class Database
{
    public static function init()
    {


        $capsule = new Capsule;

        $capsule->addConnection(Config::get('db'));
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }
}
