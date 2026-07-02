<?php
use App\Core\Router;

/** @var Router $router */

$router->get('/', 'IndexController@index')->name('home');
$router->get('/api', 'IndexController@api')->name('api.index');

// مثال روت با middleware احراز هویت (توکن رو در .env با کلید AUTH_TOKEN تنظیم کن)
// $router->post('/webhook', 'WebhookController@handle', ['Auth']);

// مثال route group
// $router->group(['prefix' => '/admin', 'middleware' => ['Auth']], function (Router $router) {
//     $router->get('/dashboard', 'AdminController@dashboard');
// });
