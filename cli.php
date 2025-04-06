<?php
namespace App\Console;

class Artisan
{
    public function __construct()
    {
        // شروع به پردازش دستورات
        $this->run();
    }

    public function run()
    {
        global $argv; // آرگومان‌های ورودی خط فرمان

        if (isset($argv[1])) {
            $command = $argv[1];         // فرمان ورودی (مثلاً make:module)
            $name    = $argv[2] ?? null; // نام ماژول (مثلاً category)

            switch ($command) {
                case 'make:module':
                    $this->makeModule($name);
                    break;
                default:
                    echo "دستور نامعتبر است.\n";
            }
        }
    }

    private function makeModule($name)
    {
        if (empty($name)) {
            echo "نام ماژول را وارد کنید.\n";
            return;
        }
        $name = ucfirst($name);

        // مسیر پوشه‌های مختلف
        $controllerPath = __DIR__ . '/app/Controllers/' . $name;
        $routesPath     = __DIR__ . '/routes/';
        $modelPath      = __DIR__ . '/app/Models/';

        // ساخت پوشه کنترلر
        if (! is_dir($controllerPath)) {
            mkdir($controllerPath, 0777, true);
        }

        // ساخت فایل‌های کنترلر
        $this->createControllerFile($controllerPath, 'ItemsController', $name);
        $this->createControllerFile($controllerPath, 'ItemController', $name);
        $this->createControllerFile($controllerPath, 'CreateController', $name);
        $this->createControllerFile($controllerPath, 'UpdateController', $name);
        $this->createControllerFile($controllerPath, 'DeleteController', $name);

        // ساخت فایل روت‌ها
        $this->createRoutesFile($routesPath, $name);

        // ساخت مدل
        $this->createModelFile($modelPath, $name);

        echo "ماژول $name با موفقیت ساخته شد.\n";
    }

    private function createControllerFile($controllerPath, $fileName, $moduleName)
    {
        $filePath = $controllerPath . '/' . $fileName . '.php';

        if (file_exists($filePath)) {
            echo "فایل $fileName قبلاً وجود دارد.\n";
            return;
        }

        $content = "<?php\n\nnamespace App\Controllers\\$moduleName;\n\nclass $fileName\n{\n    // متدهای مربوط به این کنترلر\n}\n";
        file_put_contents($filePath, $content);
    }

    private function createRoutesFile($routesPath, $moduleName)
    {
        $filePath = $routesPath . $moduleName . 'Routes.php';

        if (file_exists($filePath)) {
            echo "فایل روت‌ها قبلاً وجود دارد.\n";
            return;
        }
        $roterName = lcfirst($moduleName);

        $content = "<?php\n\nuse App\Controllers\\$moduleName\ItemsController;\n\n// تعریف روت‌ها\nRoute::get('/$roterName', [ItemsController::class, 'index']);\n";
        file_put_contents($filePath, $content);
    }

    private function createModelFile($modelPath, $moduleName)
    {
        $filePath = $modelPath . $moduleName . '.php';

        if (file_exists($filePath)) {
            echo "مدل $moduleName قبلاً وجود دارد.\n";
            return;
        }

        $content = "<?php\n\nnamespace App\Models;\n\nclass $moduleName\n{\n    // ویژگی‌ها و متدهای مدل\n}\n";
        file_put_contents($filePath, $content);
    }
}

new Artisan();
