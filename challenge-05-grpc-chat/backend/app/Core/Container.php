<?php

namespace App\Core;

class Container
{
    private array $bindings = [];
    private array $instances = [];

    public function bind(string $abstract, $concrete = null): void
    {
        if ($concrete === null) {
            $concrete = $abstract;
        }
        
        $this->bindings[$abstract] = $concrete;
    }

    public function singleton(string $abstract, $concrete = null): void
    {
        $this->bind($abstract, $concrete);
        $this->instances[$abstract] = null;
    }

    public function get(string $abstract)
    {
        // Check if we have a singleton instance
        if (isset($this->instances[$abstract]) && $this->instances[$abstract] !== null) {
            return $this->instances[$abstract];
        }
        
        // Check if we have a binding
        if (isset($this->bindings[$abstract])) {
            $concrete = $this->bindings[$abstract];
            
            // If concrete is a closure, execute it
            if ($concrete instanceof \Closure) {
                $object = $concrete($this);
            } elseif (is_string($concrete)) {
                // If concrete is a string, resolve it
                $object = $this->resolve($concrete);
            } else {
                $object = $concrete;
            }
            
            // Store singleton instance if needed
            if (isset($this->instances[$abstract])) {
                $this->instances[$abstract] = $object;
            }
            
            return $object;
        }
        
        // Try to resolve the class directly
        return $this->resolve($abstract);
    }

    private function resolve(string $concrete)
    {
        $reflector = new \ReflectionClass($concrete);
        
        if (!$reflector->isInstantiable()) {
            throw new \Exception("Class {$concrete} is not instantiable");
        }
        
        $constructor = $reflector->getConstructor();
        
        if ($constructor === null) {
            return new $concrete;
        }
        
        $parameters = $constructor->getParameters();
        $dependencies = $this->resolveDependencies($parameters);
        
        return $reflector->newInstanceArgs($dependencies);
    }

    private function resolveDependencies(array $parameters): array
    {
        $dependencies = [];
        
        foreach ($parameters as $parameter) {
            $type = $parameter->getType();
            
            if ($type === null) {
                // No type hint, check for default value
                if ($parameter->isDefaultValueAvailable()) {
                    $dependencies[] = $parameter->getDefaultValue();
                } else {
                    throw new \Exception("Cannot resolve parameter {$parameter->getName()}");
                }
            } else {
                // Resolve the type hinted class
                $dependencies[] = $this->get($type->getName());
            }
        }
        
        return $dependencies;
    }

    public function has(string $abstract): bool
    {
        return isset($this->bindings[$abstract]) || isset($this->instances[$abstract]);
    }
}