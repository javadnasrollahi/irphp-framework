# IRPHP Framework

**IRPHP** is a simple, lightweight, and extensible MVC PHP framework designed for building modern PHP web applications with clean architecture.

---

## 🚀 Features

- Clean MVC structure with a tiny DI container (auto-wiring via reflection)
- Routing: GET/POST/PUT/PATCH/DELETE, route groups, named routes, optional auto-routing
- Request object (no more raw `$_GET`/`$_POST`), fluent Response builder
- View system with layout/extend support
- Centralized exception handler (readable errors in dev, safe messages in prod via `APP_DEBUG`)
- Simple file Logger, rule-based Validator, CSRF middleware
- Database via Eloquent (`illuminate/database`) — works out of the box with **SQLite, zero config**
- Migrations (`php cli.php migrate`)
- CLI generator for modules (`php cli.php make:module Category`)
- PSR-4 autoloading (Composer ready)

---

## 🏗️ Project Structure

```
project/
│── app/
│   ├── Controllers/
│   │   ├── IndexController.php
│   │   ├── .....php
│   ├── Models/
│   │   ├── User.php
│   │   ├── .....php
│   ├── Services/
│   │   ├── ....php
│── routes/
│   ├── web.php
│── public/
│   ├── index.php
│── bootstrap.php
│── .env
│── composer.json
```

---

## ⚙️ Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/javadnasrollahi/irphp-framework.git
   cd irphp-framework
   ```

2. Install dependencies via Composer:
   ```bash
   composer install
   ```

3. Copy `.env.example` to `.env`:
   ```bash
   cp .env.example .env
   ```
   The default config uses **SQLite** (`storage/database.sqlite`, auto-created on first run) — no external database setup needed to try the framework. Switch `DB_CONNECTION` to `mysql` in `.env` if you need it.

---

## 🖥️ Run the Server

```bash
composer serve
# or directly:
php -S 127.0.0.1:8080 -t public
```

Visit [http://127.0.0.1:8080](http://127.0.0.1:8080) — you should see the welcome page rendered through the layout system.

Run migrations any time with:
```bash
php cli.php migrate
```

---

## 🧩 Routing

Define your routes in `routes/web.php` (or add any file in `routes/`, they're all auto-loaded):

```php
$router->get('/', 'IndexController@index')->name('home');

$router->group(['prefix' => '/admin', 'middleware' => ['Auth']], function ($router) {
    $router->get('/dashboard', 'AdminController@dashboard')->name('admin.dashboard');
});

$router->put('/users/{id}', 'UserController@update');
```

Supports:
- GET, POST, PUT, PATCH, DELETE methods
- Route groups (`prefix` + `middleware`) and named routes (`$router->route('admin.dashboard')`)
- Optional auto-routing mode (`AUTO_ROUTING=true` in `.env`)
- Controller methods can type-hint `App\Core\Request` to receive the current request

---

## 🧠 Controllers

Example controller:

```php
namespace App\Controllers;

use App\Core\Response;

class IndexController
{
    public function index()
    {
        return Response::make()
            ->view('index', ['name' => 'Amir'])
            ->send();
    }
}
```

---

## 🖼️ Views

View file: `app/Views/index.php`

```php
<?php \App\Core\View::extend('master'); ?>
<h1>Hello, <?= htmlspecialchars($name) ?> 👋</h1>
```

Supports layout extending and data passing.

---

## 🧪 Testing

You can run the tests using PHPUnit. First, make sure you have PHPUnit installed via Composer:

```bash
composer install --dev
```

To run the tests:

```bash
composer test
```

This will run the test suite and show the results.

Tests are located in the `tests/` directory.

---

## 💻 CLI Commands

```bash
php cli.php make:module Category     # Controller(s) + Model + routes file
php cli.php make:migration create_posts_table
php cli.php migrate
php cli.php migrate:rollback
php cli.php serve [host:port]
```

---

## 🧪 TODO (Future Plans)

- [x] Middleware support
- [x] CLI command runner
- [x] Unit testing structure
- [x] Database migrations
- [x] Dependency injection container
- [ ] REST API utilities

---

## ❤️ Contributing

Contributions are welcome! Feel free to fork the repo and submit pull requests.

---

## 📄 License

MIT © [Javad Nasrollahi](https://github.com/javadnasrollahi)
