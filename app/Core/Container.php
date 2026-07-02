<?php
namespace App\Core;

class Container
{
    protected static ?self $instance = null;

    protected array $bindings   = [];
    protected array $singletons = [];
    protected array $instances  = [];

    public static function getInstance(): self
    {
        if (! self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function bind(string $abstract, \Closure $concrete): void
    {
        $this->bindings[$abstract] = $concrete;
    }

    public function singleton(string $abstract, \Closure $concrete): void
    {
        $this->bindings[$abstract]   = $concrete;
        $this->singletons[$abstract] = true;
    }

    public function make(string $abstract)
    {
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        if (isset($this->bindings[$abstract])) {
            $object = $this->bindings[$abstract]($this);
        } else {
            $object = $this->build($abstract);
        }

        if (! empty($this->singletons[$abstract])) {
            $this->instances[$abstract] = $object;
        }

        return $object;
    }

    /**
     * ساخت خودکار کلاس با تزریق وابستگی‌ها از روی type-hint سازنده (Reflection)
     */
    protected function build(string $class)
    {
        $reflector = new \ReflectionClass($class);

        if (! $reflector->isInstantiable()) {
            throw new \RuntimeException("Class {$class} is not instantiable.");
        }

        $constructor = $reflector->getConstructor();
        if (! $constructor) {
            return new $class();
        }

        $dependencies = [];
        foreach ($constructor->getParameters() as $parameter) {
            $type = $parameter->getType();

            if ($type && ! $type->isBuiltin()) {
                $dependencies[] = $this->make($type->getName());
            } elseif ($parameter->isDefaultValueAvailable()) {
                $dependencies[] = $parameter->getDefaultValue();
            } else {
                $dependencies[] = null;
            }
        }

        return $reflector->newInstanceArgs($dependencies);
    }
}
