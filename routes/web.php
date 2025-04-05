<?php
use App\Core\Router;

// $router->post('/webhook', 'WebhookController@handle', ['Auth']);

$router->get('/', 'IndexController@index');
$router->get('/api', 'IndexController@api');
