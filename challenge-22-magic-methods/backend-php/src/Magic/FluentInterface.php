<?php

namespace App\Magic;

class FluentInterface
{
    protected array $methods = [];
    protected array $properties = [];

    public function __call(string $name, array $arguments)
    {
        // Store method calls for fluent interface
        $this->methods[$name] = $arguments;
        return $this;
    }

    public function __get(string $name)
    {
        return $this->properties[$name] ?? null;
    }

    public function __set(string $name, $value): void
    {
        $this->properties[$name] = $value;
    }

    public function __isset(string $name): bool
    {
        return isset($this->properties[$name]);
    }

    public function __unset(string $name): void
    {
        unset($this->properties[$name]);
    }

    public function __toString(): string
    {
        return json_encode([
            'methods' => $this->methods,
            'properties' => $this->properties
        ], JSON_UNESCAPED_UNICODE);
    }

    public function __invoke()
    {
        return $this->execute();
    }

    public function execute(): array
    {
        return [
            'methods' => $this->methods,
            'properties' => $this->properties
        ];
    }

    public function __clone()
    {
        // Clone the object with all its properties
        $this->properties = array_merge([], $this->properties);
        $this->methods = array_merge([], $this->methods);
    }

    public function __sleep(): array
    {
        // Return the names of properties to serialize
        return ['methods', 'properties'];
    }

    public function __wakeup(): void
    {
        // Restore the object after unserialization
    }

    public function __debugInfo(): array
    {
        return [
            'methods' => $this->methods,
            'properties' => $this->properties,
            'class' => static::class
        ];
    }
}