<?php

namespace App\Container\Contracts;

interface FactoryInterface
{
    /**
     * Create an instance of a class
     */
    public function create(string $className);

    /**
     * Create a service with specific parameters
     */
    public function createWithParameters(string $className, array $parameters);

    /**
     * Get reflection information for a class
     */
    public function getReflection(string $className): \ReflectionClass;
}