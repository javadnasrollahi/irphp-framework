<?php

if (! function_exists('env')) {
    /**
     * خواندن متغیر محیطی با تبدیل نوع درست (bool/null/int)
     */
    function env(string $key, $default = null)
    {
        $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);

        if ($value === false || $value === null) {
            return $default;
        }

        switch (strtolower((string) $value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'null':
            case '(null)':
                return null;
            case 'empty':
            case '(empty)':
                return '';
        }

        if (is_string($value) && is_numeric($value)) {
            return $value + 0; // int یا float
        }

        return $value;
    }
}

if (! function_exists('app_debug')) {
    function app_debug(): bool
    {
        return (bool) env('APP_DEBUG', false);
    }
}

if (! function_exists('base_path')) {
    function base_path(string $path = ''): string
    {
        return rtrim(__DIR__ . '/../../', '/') . ($path ? '/' . ltrim($path, '/') : '');
    }
}

if (! function_exists('storage_path')) {
    function storage_path(string $path = ''): string
    {
        return base_path('storage') . ($path ? '/' . ltrim($path, '/') : '');
    }
}

if (! function_exists('csrf_token')) {
    /**
     * تولید/بازیابی توکن CSRF از سشن
     */
    function csrf_token(): string
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['_csrf_token'];
    }
}

if (! function_exists('csrf_field')) {
    function csrf_field(): string
    {
        return '<input type="hidden" name="_token" value="' . htmlspecialchars(csrf_token()) . '">';
    }
}

if (! function_exists('dd')) {
    function dd(...$vars)
    {
        foreach ($vars as $var) {
            echo '<pre>' . htmlspecialchars(print_r($var, true)) . '</pre>';
        }
        exit(1);
    }
}
