<?php

namespace App\Container\Exception;

class CircularDependencyException extends ContainerException
{
    public function __construct(string $className)
    {
        parent::__construct("Circular dependency detected for class: {$className}");
    }
}