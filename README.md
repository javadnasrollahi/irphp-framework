# IRPHP Framework

**IRPHP** is a simple, lightweight, and extensible MVC PHP framework designed for building modern PHP web applications with clean architecture.

---

## 🚀 Features

- Clean MVC structure
- Simple and customizable routing system (with optional auto-routing)
- Powerful and fluent HTTP Response system
- View system with layout/extend support
- `.env` file support
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

3. Copy `.env.example` to `.env` and configure your environment variables.

---

## 🖥️ Run the Server

Run the built-in PHP server using:

```bash
php -S 127.0.0.1:8080 -t public
```

Visit [http://127.0.0.1:8080](http://127.0.0.1:8080) in your browser.

---

## 🧩 Routing

Define your routes in `routes/webhook.php` or other route files:

```php
$router->get('/', 'IndexController@index');
```

Supports:
- GET, POST, PUT, DELETE methods
- Optional auto-routing mode (configurable)

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

You can use the CLI tool to generate modules, controllers, and other components automatically.

To create a new module (e.g., category):

```bash
composer cli category
```

This will generate:

- Controller files: `items.php`, `item.php`, `create.php`, `update.php`, `delete.php`
- Model file
- Routes file

This helps speed up the development process by generating boilerplate code for you.

---

## 🧪 TODO (Future Plans)

- [x] Middleware support  
- [x] CLI command runner  
- [x] Unit testing structure
- [ ] Database migrations  
- [ ] Dependency injection container  
- [ ] REST API utilities  

---

## ❤️ Contributing

Contributions are welcome! Feel free to fork the repo and submit pull requests.

---

## 📄 License

MIT © [Javad Nasrollahi](https://github.com/javadnasrollahi)
