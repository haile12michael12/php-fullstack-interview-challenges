<?php

namespace App\Container\Core;

use App\Container\Contracts\ResolverInterface;
use App\Container\Contracts\ContainerInterface;
use App\Container\Exception\ContainerException;

class ReflectionResolver implements ResolverInterface
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function resolve(string $className)
    {
        try {
            $reflection = new \ReflectionClass($className);
            
            // If class has no constructor, just instantiate it
            if (!$reflection->hasMethod('__construct')) {
                return $reflection->newInstance();
            }
            
            // Get constructor
            $constructor = $reflection->getConstructor();
            
            // If constructor has no parameters, just instantiate it
            if ($constructor->getNumberOfParameters() === 0) {
                return $reflection->newInstance();
            }
            
            // Resolve constructor parameters
            $parameters = $this->resolveParameters($constructor);
            
            // Create instance with resolved parameters
            return $reflection->newInstanceArgs($parameters);
        } catch (\ReflectionException $e) {
            throw new ContainerException("Failed to resolve class {$className}: " . $e->getMessage(), 0, $e);
        }
    }

    public function resolveParameters(\ReflectionMethod $constructor): array
    {
        $parameters = [];
        
        foreach ($constructor->getParameters() as $parameter) {
            $parameters[] = $this->resolveParameter($parameter);
        }
        
        return $parameters;
    }

    public function resolveParameter(\ReflectionParameter $parameter)
    {
        // If parameter has a type hint
        if ($parameter->hasType()) {
            $type = $parameter->getType();
            
            // Handle built-in types
            if ($type instanceof \ReflectionNamedType && $type->isBuiltin()) {
                // If parameter has a default value
                if ($parameter->isDefaultValueAvailable()) {
                    return $parameter->getDefaultValue();
                }
                
                // For scalar types, we can't resolve automatically
                throw new ContainerException("Cannot resolve built-in parameter: {$parameter->getName()}");
            }
            
            // Handle class types
            if ($type instanceof \ReflectionNamedType) {
                $typeName = $type->getName();
                
                // Try to get from container
                if ($this->container->has($typeName)) {
                    return $this->container->get($typeName);
                }
                
                // Try to resolve class directly
                return $this->resolve($typeName);
            }
        }
        
        // If parameter has a default value
        if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }
        
        throw new ContainerException("Cannot resolve parameter: {$parameter->getName()}");
    }
}