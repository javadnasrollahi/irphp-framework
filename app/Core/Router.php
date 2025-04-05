<?php
namespace App\Core;

class Router
{
    protected array $routes = [];
    protected bool $autoRouting;
    protected string $namespace = 'App\\Controllers\\';

    public function __construct(bool $autoRouting = false)
    {
        $this->autoRouting = $autoRouting;
    }

    public function get(string $uri, $action, array $middleware = [])
    {
        $this->addRoute('GET', $uri, $action, $middleware);
    }

    public function post(string $uri, $action, array $middleware = [])
    {
        $this->addRoute('POST', $uri, $action, $middleware);
    }

    public function addRoute(string $method, string $uri, $action, array $middleware = [])
    {
        $this->routes[$method][$uri] = compact('action', 'middleware');
    }

    public function dispatch(string $method, string $uri)
    {
        $uri          = parse_url($uri, PHP_URL_PATH);
        $methodRoutes = $this->routes[$method] ?? [];

        foreach ($methodRoutes as $route => $data) {
            $pattern = "@^" . preg_replace('/\{(\w+)\}/', '(?P<\1>[^/]+)', $route) . "$@D";
            if (preg_match($pattern, $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                return $this->handle($data['action'], $data['middleware'], $params);
            }
        }

        if ($this->autoRouting) {
            return $this->autoRoute($uri);
        }

        http_response_code(404);
        echo "404 - Not Found";
    }

    protected function handle($action, array $middleware, array $params)
    {
        foreach ($middleware as $m) {
            $middlewareClass = "App\\Middleware\\$m";
            if (class_exists($middlewareClass)) {
                (new $middlewareClass())->handle();
            }
        }

        if (is_callable($action)) {
            return call_user_func_array($action, $params);
        }

        if (is_string($action)) {
            [$controller, $method] = explode('@', $action);
            $class                 = $this->namespace . $controller;
            if (class_exists($class)) {
                $instance = new $class();
                return call_user_func_array([$instance, $method], $params);
            }
        }

        throw new \Exception("Invalid route action");
    }

    protected function autoRoute(string $uri)
    {
        $segments   = array_values(array_filter(explode('/', trim($uri, '/'))));
        $controller = ucfirst($segments[0] ?? 'Home') . 'Controller';
        $method     = $segments[1] ?? 'index';
        $params     = array_slice($segments, 2);

        $class = $this->namespace . $controller;
        if (class_exists($class) && method_exists($class, $method)) {
            $instance = new $class();
            return call_user_func_array([$instance, $method], $params);
        }

        http_response_code(404);
        echo "404 - Not Found (Auto Routing)";
    }
}
