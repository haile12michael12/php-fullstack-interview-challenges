<?php

namespace App\Container\Contracts;

interface ResolverInterface
{
    /**
     * Resolve a class using reflection
     */
    public function resolve(string $className);

    /**
     * Resolve constructor parameters
     */
    public function resolveParameters(\ReflectionMethod $constructor): array;

    /**
     * Resolve a parameter
     */
    public function resolveParameter(\ReflectionParameter $parameter);
}