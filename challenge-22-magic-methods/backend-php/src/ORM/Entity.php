<?php

namespace App\ORM;

abstract class Entity
{
    protected array $attributes = [];
    protected array $original = [];
    protected bool $exists = false;

    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
        $this->syncOriginal();
    }

    public function fill(array $attributes): void
    {
        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value);
        }
    }

    public function getAttribute(string $key)
    {
        return $this->attributes[$key] ?? null;
    }

    public function setAttribute(string $key, $value): void
    {
        $this->attributes[$key] = $value;
    }

    public function syncOriginal(): void
    {
        $this->original = $this->attributes;
    }

    public function getOriginal(string $key)
    {
        return $this->original[$key] ?? null;
    }

    public function isDirty(string $key = null): bool
    {
        if ($key === null) {
            return $this->attributes !== $this->original;
        }
        
        return $this->getAttribute($key) !== $this->getOriginal($key);
    }

    public function __get(string $name)
    {
        return $this->getAttribute($name);
    }

    public function __set(string $name, $value): void
    {
        $this->setAttribute($name, $value);
    }

    public function __isset(string $name): bool
    {
        return isset($this->attributes[$name]);
    }

    public function __unset(string $name): void
    {
        unset($this->attributes[$name]);
    }

    public function __toString(): string
    {
        return json_encode($this->attributes, JSON_UNESCAPED_UNICODE);
    }

    public function toArray(): array
    {
        return $this->attributes;
    }

    public function toJson(int $options = 0): string
    {
        return json_encode($this->attributes, $options);
    }
}