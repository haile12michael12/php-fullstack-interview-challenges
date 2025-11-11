<?php

namespace App\ORM;

abstract class Relation
{
    protected Model $parent;
    protected Model $related;
    protected string $foreignKey;
    protected string $localKey;

    public function __construct(Model $parent, Model $related, string $foreignKey, string $localKey)
    {
        $this->parent = $parent;
        $this->related = $related;
        $this->foreignKey = $foreignKey;
        $this->localKey = $localKey;
    }

    abstract public function getResults();

    public function __call(string $method, array $parameters)
    {
        // Delegate calls to the related model's query builder
        $query = $this->related->newQuery();
        return $query->$method(...$parameters);
    }

    public function __get(string $name)
    {
        // Delegate property access to the related model
        return $this->related->$name;
    }

    public function __set(string $name, $value): void
    {
        // Delegate property setting to the related model
        $this->related->$name = $value;
    }

    public function __isset(string $name): bool
    {
        return isset($this->related->$name);
    }

    public function __unset(string $name): void
    {
        unset($this->related->$name);
    }

    public function __toString(): string
    {
        return (string)$this->getResults();
    }

    public function __invoke()
    {
        return $this->getResults();
    }

    public function getParent(): Model
    {
        return $this->parent;
    }

    public function getRelated(): Model
    {
        return $this->related;
    }

    public function getForeignKey(): string
    {
        return $this->foreignKey;
    }

    public function getLocalKey(): string
    {
        return $this->localKey;
    }
}