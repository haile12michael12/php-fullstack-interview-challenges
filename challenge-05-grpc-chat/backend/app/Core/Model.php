<?php

namespace App\Core;

abstract class Model
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

    public function setAttribute(string $key, $value): void
    {
        $this->attributes[$key] = $value;
    }

    public function getAttribute(string $key)
    {
        return $this->attributes[$key] ?? null;
    }

    public function syncOriginal(): void
    {
        $this->original = $this->attributes;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getOriginal(): array
    {
        return $this->original;
    }

    public function isDirty(): bool
    {
        return $this->attributes !== $this->original;
    }

    public function isClean(): bool
    {
        return !$this->isDirty();
    }

    public function __get(string $key)
    {
        return $this->getAttribute($key);
    }

    public function __set(string $key, $value): void
    {
        $this->setAttribute($key, $value);
    }

    public function toArray(): array
    {
        return $this->attributes;
    }

    public function toJson(int $options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }
}