<?php
namespace App\Core;

class View
{
    protected static string $viewPath = __DIR__ . '/../Views/';
    protected static string $layout   = '';

    public static function make(string $view, array $data = [])
    {
        $viewFile = static::$viewPath . $view . '.php';
        if (! file_exists($viewFile)) {
            throw new \RuntimeException("View [$view] not found!");
        }

        extract($data);
        ob_start();
        include $viewFile;
        $content = ob_get_clean();

        if (static::$layout) {
            $layoutFile     = static::$viewPath . 'layouts/' . static::$layout . '.php';
            static::$layout = ''; // 👈 بعد از استفاده، پاکش می‌کنیم

            if (file_exists($layoutFile)) {
                ob_start();
                include $layoutFile;
                return ob_get_clean();
            } else {
                throw new \RuntimeException("Layout [{$layoutFile}] not found!");
            }
        }

        return $content;
    }

    public static function extend(string $layout)
    {
        static::$layout = $layout;
    }
}
