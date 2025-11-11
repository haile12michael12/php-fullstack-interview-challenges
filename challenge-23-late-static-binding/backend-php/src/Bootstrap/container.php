<?php

// Simple dependency injection container
class Container
{
    protected static $instance = null;
    protected $bindings = [];
    protected $instances = [];

    protected function __construct()
    {
        // Singleton pattern
    }

    public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public static function bind($abstract, $concrete = null)
    {
        if ($concrete === null) {
            $concrete = $abstract;
        }
        
        static::getInstance()->bindings[$abstract] = $concrete;
    }

    public static function singleton($abstract, $concrete = null)
    {
        if ($concrete === null) {
            $concrete = $abstract;
        }
        
        static::getInstance()->bindings[$abstract] = function () use ($concrete) {
            static $instance = null;
            if ($instance === null) {
                $instance = static::getInstance()->build($concrete);
            }
            return $instance;
        };
    }

    public static function resolve($abstract)
    {
        return static::getInstance()->make($abstract);
    }

    public function make($abstract)
    {
        // Check if we have a singleton instance
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }
        
        // Check if we have a binding
        if (isset($this->bindings[$abstract])) {
            $concrete = $this->bindings[$abstract];
            
            // If it's a closure, execute it
            if ($concrete instanceof Closure) {
                $object = $concrete();
            } else {
                $object = $this->build($concrete);
            }
            
            // Store singleton instances
            if (is_callable($this->bindings[$abstract]) && strpos((string)$this->bindings[$abstract], '{closure}') !== false) {
                $this->instances[$abstract] = $object;
            }
            
            return $object;
        }
        
        // Try to build the class directly
        return $this->build($abstract);
    }

    protected function build($concrete)
    {
        // If it's already an object, return it
        if ($concrete instanceof Closure || !is_string($concrete)) {
            return $concrete;
        }
        
        // Create a new instance using reflection
        $reflector = new ReflectionClass($concrete);
        
        if (!$reflector->isInstantiable()) {
            throw new Exception("Class {$concrete} is not instantiable");
        }
        
        $constructor = $reflector->getConstructor();
        
        if ($constructor === null) {
            return new $concrete;
        }
        
        $parameters = $constructor->getParameters();
        $dependencies = $this->resolveDependencies($parameters);
        
        return $reflector->newInstanceArgs($dependencies);
    }

    protected function resolveDependencies($parameters)
    {
        $dependencies = [];
        
        foreach ($parameters as $parameter) {
            $type = $parameter->getType();
            
            if ($type === null) {
                if ($parameter->isDefaultValueAvailable()) {
                    $dependencies[] = $parameter->getDefaultValue();
                } else {
                    throw new Exception("Cannot resolve parameter {$parameter->getName()}");
                }
            } else {
                $dependencies[] = $this->make($type->getName());
            }
        }
        
        return $dependencies;
    }
}

// Helper functions
function app($abstract = null)
{
    if ($abstract === null) {
        return Container::getInstance();
    }
    
    return Container::getInstance()->make($abstract);
}

function bind($abstract, $concrete = null)
{
    Container::getInstance()->bind($abstract, $concrete);
}

function singleton($abstract, $concrete = null)
{
    Container::getInstance()->singleton($abstract, $concrete);
}

function resolve($abstract)
{
    return Container::getInstance()->make($abstract);
}