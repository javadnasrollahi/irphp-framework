<?php
namespace App\Core;

class Router
{
    protected array $routes      = [];
    protected array $namedRoutes = [];
    protected bool $autoRouting;
    protected string $namespace = 'App\\Controllers\\';
    protected Container $container;

    // برای route groups
    protected string $groupPrefix    = '';
    protected array $groupMiddleware = [];
    protected ?string $pendingName   = null;

    public function __construct(bool $autoRouting = false, ?Container $container = null)
    {
        $this->autoRouting = $autoRouting;
        $this->container   = $container ?? Container::getInstance();
    }

    public function get(string $uri, $action, array $middleware = []): self
    {
        return $this->addRoute('GET', $uri, $action, $middleware);
    }

    public function post(string $uri, $action, array $middleware = []): self
    {
        return $this->addRoute('POST', $uri, $action, $middleware);
    }

    public function put(string $uri, $action, array $middleware = []): self
    {
        return $this->addRoute('PUT', $uri, $action, $middleware);
    }

    public function patch(string $uri, $action, array $middleware = []): self
    {
        return $this->addRoute('PATCH', $uri, $action, $middleware);
    }

    public function delete(string $uri, $action, array $middleware = []): self
    {
        return $this->addRoute('DELETE', $uri, $action, $middleware);
    }

    /**
     * گروه‌بندی روت‌ها با prefix و middleware مشترک
     * $router->group(['prefix' => '/admin', 'middleware' => ['Auth']], function ($router) {
     *     $router->get('/dashboard', 'AdminController@dashboard');
     * });
     */
    public function group(array $attributes, \Closure $callback): void
    {
        $previousPrefix     = $this->groupPrefix;
        $previousMiddleware = $this->groupMiddleware;

        $this->groupPrefix     = $previousPrefix . ($attributes['prefix'] ?? '');
        $this->groupMiddleware = array_merge($previousMiddleware, $attributes['middleware'] ?? []);

        $callback($this);

        $this->groupPrefix     = $previousPrefix;
        $this->groupMiddleware = $previousMiddleware;
    }

    /**
     * اسم‌گذاری آخرین روت ثبت‌شده برای استفاده در route()
     * $router->get('/users/{id}', 'UserController@show')->name('users.show');
     */
    public function name(string $name): self
    {
        if ($this->pendingName) {
            $this->namedRoutes[$name] = $this->pendingName;
        }
        return $this;
    }

    /**
     * ساخت URL از روی نام روت: route('users.show', ['id' => 5])
     */
    public function route(string $name, array $params = []): string
    {
        if (! isset($this->namedRoutes[$name])) {
            throw new \RuntimeException("Route [{$name}] not found.");
        }

        $uri = $this->namedRoutes[$name];
        foreach ($params as $key => $value) {
            $uri = str_replace('{' . $key . '}', (string) $value, $uri);
        }

        return $uri;
    }

    public function addRoute(string $method, string $uri, $action, array $middleware = []): self
    {
        $fullUri    = rtrim($this->groupPrefix, '/') . '/' . ltrim($uri, '/');
        $fullUri    = $fullUri === '' ? '/' : $fullUri;
        $middleware = array_merge($this->groupMiddleware, $middleware);

        $this->routes[$method][$fullUri] = compact('action', 'middleware');
        $this->pendingName               = $fullUri;

        return $this;
    }

    public function dispatch(string $method, string $uri, ?Request $request = null)
    {
        $request      = $request ?? Request::capture();
        $uri          = parse_url($uri, PHP_URL_PATH);
        $methodRoutes = $this->routes[$method] ?? [];

        foreach ($methodRoutes as $route => $data) {
            $pattern = "@^" . preg_replace('/\{(\w+)\}/', '(?P<\1>[^/]+)', $route) . "$@D";
            if (preg_match($pattern, $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                return $this->handle($data['action'], $data['middleware'], $params, $request);
            }
        }

        if ($this->autoRouting) {
            return $this->autoRoute($uri, $request);
        }

        http_response_code(404);
        echo "404 - Not Found";
    }

    protected function handle($action, array $middleware, array $params, Request $request)
    {
        foreach ($middleware as $m) {
            [$name, $argsString] = array_pad(explode(':', $m, 2), 2, null);
            $middlewareClass     = "App\\Middleware\\$name";

            if (class_exists($middlewareClass)) {
                $args = $argsString ? explode(',', $argsString) : [];
                call_user_func_array([$this->container->make($middlewareClass), 'handle'], $args);
            }
        }

        if (is_callable($action)) {
            return call_user_func_array($action, array_merge($params, ['request' => $request]));
        }

        if (is_string($action)) {
            [$controller, $method] = explode('@', $action);
            $class                 = $this->namespace . $controller;
            if (class_exists($class)) {
                $instance = $this->container->make($class);
                return $this->invoke($instance, $method, $params, $request);
            }
        }

        throw new \Exception("Invalid route action");
    }

    /**
     * صدا زدن متد کنترلر و تزریق Request فقط اگر پارامتری از نوع Request داشته باشه
     */
    protected function invoke(object $instance, string $method, array $params, Request $request)
    {
        $reflection = new \ReflectionMethod($instance, $method);
        $args       = [];

        foreach ($reflection->getParameters() as $parameter) {
            $type = $parameter->getType();
            if ($type && $type->getName() === Request::class) {
                $args[] = $request;
            } elseif (array_key_exists($parameter->getName(), $params)) {
                $args[] = $params[$parameter->getName()];
            } elseif (! empty($params)) {
                $args[] = array_shift($params);
            }
        }

        return call_user_func_array([$instance, $method], $args ?: $params);
    }

    protected function autoRoute(string $uri, Request $request)
    {
        $segments   = array_values(array_filter(explode('/', trim($uri, '/'))));
        $controller = ucfirst($segments[0] ?? 'Home') . 'Controller';
        $method     = $segments[1] ?? 'index';
        $params     = array_slice($segments, 2);

        $class = $this->namespace . $controller;
        if (class_exists($class) && method_exists($class, $method)) {
            $instance = $this->container->make($class);
            return $this->invoke($instance, $method, $params, $request);
        }

        http_response_code(404);
        echo "404 - Not Found (Auto Routing)";
    }
}
