<?php

namespace App\Database;

abstract class Migration
{
    protected Connection $connection;
    protected string $name;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        $this->name = static::class;
    }

    abstract public function up(): void;
    abstract public function down(): void;

    public function __call(string $name, array $arguments)
    {
        // Handle schema creation methods
        if ($name === 'create') {
            $table = $arguments[0];
            $callback = $arguments[1];
            
            $schema = new Schema($this->connection, $table);
            $callback($schema);
            
            return $schema;
        }
        
        // Handle schema modification methods
        if ($name === 'table') {
            $table = $arguments[0];
            $callback = $arguments[1];
            
            $schema = new Schema($this->connection, $table);
            $callback($schema);
            
            return $schema;
        }
        
        throw new \BadMethodCallException("Method {$name} does not exist.");
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

    public function __toString(): string
    {
        return "Migration: {$this->name}";
    }

    public function getConnection(): Connection
    {
        return $this->connection;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function run(string $direction = 'up'): void
    {
        if ($direction === 'up') {
            $this->up();
        } else {
            $this->down();
        }
    }

    protected function schema(): Schema
    {
        return new Schema($this->connection, '');
    }
}