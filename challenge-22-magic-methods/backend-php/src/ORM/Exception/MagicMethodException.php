<?php

namespace App\ORM\Exception;

class MagicMethodException extends ORMException
{
    public function __construct(string $method, string $class)
    {
        parent::__construct("Magic method {$method} is not supported in class {$class}");
    }

    public function __toString(): string
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
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