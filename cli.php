<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Core\Migrator;
use App\Services\Database;
use Dotenv\Dotenv;

if (file_exists(__DIR__ . '/.env')) {
    Dotenv::createImmutable(__DIR__)->load();
}

class Console
{
    public function run(array $argv): void
    {
        $command = $argv[1] ?? null;
        $arg     = $argv[2] ?? null;

        switch ($command) {
            case 'make:module':
                $this->makeModule($arg);
                break;
            case 'make:migration':
                $this->makeMigration($arg);
                break;
            case 'migrate':
                Database::init();
                $applied = (new Migrator())->migrate();
                echo $applied ? "اجرا شد:\n- " . implode("\n- ", $applied) . "\n" : "چیزی برای اجرا نبود.\n";
                break;
            case 'migrate:rollback':
                Database::init();
                $reverted = (new Migrator())->rollback();
                echo $reverted ? "برگردانده شد:\n- " . implode("\n- ", $reverted) . "\n" : "چیزی برای rollback نبود.\n";
                break;
            case 'serve':
                $host = $arg ?? '127.0.0.1:8080';
                echo "Server running at http://{$host}\n";
                passthru('php -S ' . escapeshellarg($host) . ' -t public');
                break;
            default:
                $this->help();
        }
    }

    protected function help(): void
    {
        echo <<<TXT
دستورات موجود:
  make:module {name}       ساخت یک ماژول کامل (Controller + Model + Route)
  make:migration {name}    ساخت یک فایل migration خالی
  migrate                  اجرای migration های اجرا نشده
  migrate:rollback         برگرداندن آخرین batch اجرا شده
  serve [host:port]        اجرای سرور توسعه (پیش‌فرض 127.0.0.1:8080)

TXT;
    }

    protected function makeModule(?string $name): void
    {
        if (empty($name)) {
            echo "نام ماژول را وارد کنید. مثال: php cli.php make:module Category\n";
            return;
        }

        $name  = ucfirst($name);
        $lower = lcfirst($name);

        $controllerPath = __DIR__ . '/app/Controllers/' . $name;
        if (! is_dir($controllerPath)) {
            mkdir($controllerPath, 0777, true);
        }

        foreach (['Items', 'Item', 'Create', 'Update', 'Delete'] as $action) {
            $this->createControllerFile($controllerPath, $action . 'Controller', $name);
        }

        $this->createModelFile(__DIR__ . '/app/Models/', $name);
        $this->createRoutesFile(__DIR__ . '/routes/', $name, $lower);

        echo "ماژول {$name} با موفقیت ساخته شد.\n";
        echo "یادت نره فایل routes/{$name}Routes.php رو داخل routes/web.php require کنی (یا در پوشه routes بذاری چون به‌صورت خودکار لود میشه).\n";
    }

    protected function createControllerFile(string $dir, string $fileName, string $moduleName): void
    {
        $filePath = $dir . '/' . $fileName . '.php';
        if (file_exists($filePath)) {
            echo "فایل {$fileName} قبلاً وجود دارد.\n";
            return;
        }

        $content = <<<PHP
<?php

namespace App\Controllers\\{$moduleName};

use App\Core\Response;
use App\Core\Request;

class {$fileName}
{
    public function index(Request \$request)
    {
        return Response::make()->json(['module' => '{$moduleName}'])->send();
    }
}

PHP;
        file_put_contents($filePath, $content);
    }

    protected function createRoutesFile(string $routesPath, string $moduleName, string $lower): void
    {
        $filePath = $routesPath . $moduleName . 'Routes.php';
        if (file_exists($filePath)) {
            echo "فایل روت‌ها قبلاً وجود دارد.\n";
            return;
        }

        $content = <<<PHP
<?php

use App\Core\Router;

/** @var Router \$router */
\$router->group(['prefix' => '/{$lower}'], function (Router \$router) {
    \$router->get('/', '{$moduleName}\\ItemsController@index');
});

PHP;
        file_put_contents($filePath, $content);
    }

    protected function createModelFile(string $modelPath, string $moduleName): void
    {
        $filePath = $modelPath . $moduleName . '.php';
        if (file_exists($filePath)) {
            echo "مدل {$moduleName} قبلاً وجود دارد.\n";
            return;
        }

        $content = <<<PHP
<?php

namespace App\Models;

class {$moduleName} extends BaseModel
{
    protected \$table = '{$moduleName}';
    protected \$fillable = [];
}

PHP;
        file_put_contents($filePath, $content);
    }

    protected function makeMigration(?string $name): void
    {
        if (empty($name)) {
            echo "نام migration را وارد کنید. مثال: php cli.php make:migration create_posts_table\n";
            return;
        }

        $className = str_replace(' ', '', ucwords(str_replace('_', ' ', $name)));
        $fileName  = date('Y_m_d_His') . '_' . $name;
        $dir       = __DIR__ . '/database/migrations';

        if (! is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $content = <<<PHP
<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class {$className}
{
    public function up(): void
    {
        //
    }

    public function down(): void
    {
        //
    }
}

PHP;
        file_put_contents($dir . '/' . $fileName . '.php', $content);
        echo "migration ساخته شد: database/migrations/{$fileName}.php\n";
    }
}

(new Console())->run($argv);
