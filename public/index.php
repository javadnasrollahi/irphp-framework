<?php

require_once __DIR__ . '/../bootstrap.php';

use App\Config\Config;
use App\Core\Router;

$router = new Router(Config::get('auto_routing') ?? false);

// لود همه routeها
foreach (glob(__DIR__ . '/../routes/*.php') as $file) {
    require $file;
}

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
