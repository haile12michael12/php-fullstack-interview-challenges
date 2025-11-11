<?php

namespace App\Magic;

class DynamicProxy
{
    protected object $target;
    protected array $interceptors = [];

    public function __construct(object $target)
    {
        $this->target = $target;
    }

    public function __call(string $name, array $arguments)
    {
        // Check if there's an interceptor for this method
        if (isset($this->interceptors[$name])) {
            $interceptor = $this->interceptors[$name];
            return $interceptor($this->target, $name, $arguments);
        }

        // Delegate to the target object
        if (method_exists($this->target, $name)) {
            return $this->target->$name(...$arguments);
        }

        throw new \BadMethodCallException("Method {$name} does not exist.");
    }

    public function __get(string $name)
    {
        return $this->target->$name ?? null;
    }

    public function __set(string $name, $value): void
    {
        $this->target->$name = $value;
    }

    public function __isset(string $name): bool
    {
        return isset($this->target->$name);
    }

    public function __unset(string $name): void
    {
        unset($this->target->$name);
    }

    public function __toString(): string
    {
        if (method_exists($this->target, '__toString')) {
            return (string)$this->target;
        }
        
        return static::class . ' proxy for ' . get_class($this->target);
    }

    public function addInterceptor(string $method, callable $interceptor): void
    {
        $this->interceptors[$method] = $interceptor;
    }

    public function removeInterceptor(string $method): void
    {
        unset($this->interceptors[$method]);
    }

    public function getTarget(): object
    {
        return $this->target;
    }

    public function __clone()
    {
        $this->target = clone $this->target;
    }

    public function __sleep(): array
    {
        // We can't serialize the target object directly
        throw new \RuntimeException('DynamicProxy cannot be serialized');
    }

    public function __wakeup(): void
    {
        // We can't unserialize the target object directly
        throw new \RuntimeException('DynamicProxy cannot be unserialized');
    }
}