<?php

namespace App\Magic;

class MethodInterceptor
{
    protected array $beforeInterceptors = [];
    protected array $afterInterceptors = [];
    protected array $aroundInterceptors = [];

    public function __call(string $name, array $arguments)
    {
        // Handle interceptor registration methods
        if (strpos($name, 'before') === 0) {
            $method = lcfirst(substr($name, 6));
            return $this->addBeforeInterceptor($method, $arguments[0]);
        }
        
        if (strpos($name, 'after') === 0) {
            $method = lcfirst(substr($name, 5));
            return $this->addAfterInterceptor($method, $arguments[0]);
        }
        
        if (strpos($name, 'around') === 0) {
            $method = lcfirst(substr($name, 6));
            return $this->addAroundInterceptor($method, $arguments[0]);
        }
        
        throw new \BadMethodCallException("Method {$name} does not exist.");
    }

    public function addBeforeInterceptor(string $method, callable $interceptor): self
    {
        if (!isset($this->beforeInterceptors[$method])) {
            $this->beforeInterceptors[$method] = [];
        }
        $this->beforeInterceptors[$method][] = $interceptor;
        return $this;
    }

    public function addAfterInterceptor(string $method, callable $interceptor): self
    {
        if (!isset($this->afterInterceptors[$method])) {
            $this->afterInterceptors[$method] = [];
        }
        $this->afterInterceptors[$method][] = $interceptor;
        return $this;
    }

    public function addAroundInterceptor(string $method, callable $interceptor): self
    {
        if (!isset($this->aroundInterceptors[$method])) {
            $this->aroundInterceptors[$method] = [];
        }
        $this->aroundInterceptors[$method][] = $interceptor;
        return $this;
    }

    public function intercept(object $target, string $method, array $arguments = [])
    {
        // Execute before interceptors
        if (isset($this->beforeInterceptors[$method])) {
            foreach ($this->beforeInterceptors[$method] as $interceptor) {
                $interceptor($target, $method, $arguments);
            }
        }

        // Execute around interceptors or the actual method
        if (isset($this->aroundInterceptors[$method])) {
            $result = null;
            foreach ($this->aroundInterceptors[$method] as $interceptor) {
                $result = $interceptor($target, $method, $arguments, function ($target, $method, $args) {
                    return $target->$method(...$args);
                });
            }
        } else {
            // Execute the actual method
            $result = $target->$method(...$arguments);
        }

        // Execute after interceptors
        if (isset($this->afterInterceptors[$method])) {
            foreach ($this->afterInterceptors[$method] as $interceptor) {
                $interceptor($target, $method, $arguments, $result);
            }
        }

        return $result;
    }

    public function __toString(): string
    {
        return json_encode([
            'before' => array_keys($this->beforeInterceptors),
            'after' => array_keys($this->afterInterceptors),
            'around' => array_keys($this->aroundInterceptors)
        ], JSON_UNESCAPED_UNICODE);
    }

    public function __invoke(object $target, string $method, array $arguments = [])
    {
        return $this->intercept($target, $method, $arguments);
    }

    public function __get(string $property)
    {
        return $this->$property ?? null;
    }

    public function __set(string $property, $value): void
    {
        $this->$property = $value;
    }

    public function __isset(string $property): bool
    {
        return isset($this->$property);
    }

    public function __unset(string $property): void
    {
        unset($this->$property);
    }
}