<?php

namespace App\Container\Core;

use App\Container\Contracts\FactoryInterface;
use App\Container\Contracts\ResolverInterface;
use App\Container\Exception\CircularDependencyException;

class ServiceFactory implements FactoryInterface
{
    private ResolverInterface $resolver;
    private array $reflectionCache = [];
    private array $resolving = [];

    public function __construct(ResolverInterface $resolver)
    {
        $this->resolver = $resolver;
    }

    public function create(string $className)
    {
        return $this->resolver->resolve($className);
    }

    public function createWithParameters(string $className, array $parameters)
    {
        try {
            $reflection = $this->getReflection($className);
            return $reflection->newInstanceArgs($parameters);
        } catch (\ReflectionException $e) {
            throw new \RuntimeException("Failed to create instance of {$className}: " . $e->getMessage(), 0, $e);
        }
    }

    public function getReflection(string $className): \ReflectionClass
    {
        // Check for circular dependency
        if (isset($this->resolving[$className])) {
            throw new CircularDependencyException($className);
        }

        // Return cached reflection if available
        if (!isset($this->reflectionCache[$className])) {
            $this->resolving[$className] = true;
            $this->reflectionCache[$className] = new \ReflectionClass($className);
            unset($this->resolving[$className]);
        }

        return $this->reflectionCache[$className];
    }

    public function getResolver(): ResolverInterface
    {
        return $this->resolver;
    }
}