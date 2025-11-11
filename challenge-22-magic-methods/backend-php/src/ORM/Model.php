<?php

namespace App\ORM;

use App\Database\Connection;

abstract class Model extends Entity
{
    protected static string $table;
    protected static array $fillable = [];
    protected static array $hidden = [];
    protected string $primaryKey = 'id';
    protected bool $incrementing = true;
    protected string $keyType = 'int';
    protected Connection $connection;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->connection = new Connection();
    }

    public static function __callStatic(string $method, array $parameters)
    {
        // Create a new instance and call the method
        $instance = new static();
        return $instance->$method(...$parameters);
    }

    public function __call(string $method, array $parameters)
    {
        // Check if it's a query builder method
        if (method_exists(QueryBuilder::class, $method)) {
            $query = new QueryBuilder(static::$table, $this->connection);
            return $query->$method(...$parameters);
        }
        
        throw new \BadMethodCallException("Method {$method} does not exist.");
    }

    public function save(): bool
    {
        if ($this->exists) {
            return $this->update();
        }
        
        return $this->insert();
    }

    protected function insert(): bool
    {
        $attributes = $this->getAttributesForInsert();
        $columns = array_keys($attributes);
        $values = array_values($attributes);
        $placeholders = str_repeat('?,', count($values) - 1) . '?';
        
        $sql = "INSERT INTO " . static::$table . " (" . implode(',', $columns) . ") VALUES ({$placeholders})";
        $stmt = $this->connection->prepare($sql);
        
        if ($stmt->execute($values)) {
            if ($this->incrementing) {
                $this->setAttribute($this->primaryKey, $this->connection->lastInsertId());
            }
            $this->exists = true;
            $this->syncOriginal();
            return true;
        }
        
        return false;
    }

    protected function update(): bool
    {
        if (!$this->isDirty()) {
            return true;
        }
        
        $attributes = $this->getAttributesForUpdate();
        $columns = array_keys($attributes);
        $values = array_values($attributes);
        
        $setClause = implode(' = ?, ', $columns) . ' = ?';
        $sql = "UPDATE " . static::$table . " SET {$setClause} WHERE {$this->primaryKey} = ?";
        
        $stmt = $this->connection->prepare($sql);
        $values[] = $this->getAttribute($this->primaryKey);
        
        if ($stmt->execute($values)) {
            $this->syncOriginal();
            return true;
        }
        
        return false;
    }

    public function delete(): bool
    {
        if (!$this->exists) {
            return false;
        }
        
        $sql = "DELETE FROM " . static::$table . " WHERE {$this->primaryKey} = ?";
        $stmt = $this->connection->prepare($sql);
        $result = $stmt->execute([$this->getAttribute($this->primaryKey)]);
        
        if ($result) {
            $this->exists = false;
        }
        
        return $result;
    }

    protected function getAttributesForInsert(): array
    {
        $attributes = $this->attributes;
        
        if ($this->incrementing && isset($attributes[$this->primaryKey])) {
            unset($attributes[$this->primaryKey]);
        }
        
        return array_intersect_key($attributes, array_flip(static::$fillable));
    }

    protected function getAttributesForUpdate(): array
    {
        $dirty = [];
        foreach ($this->attributes as $key => $value) {
            if ($this->isDirty($key) && $key !== $this->primaryKey && in_array($key, static::$fillable)) {
                $dirty[$key] = $value;
            }
        }
        return $dirty;
    }

    public static function find($id)
    {
        $instance = new static();
        $sql = "SELECT * FROM " . static::$table . " WHERE {$instance->primaryKey} = ?";
        $stmt = $instance->connection->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        
        if ($result) {
            $instance->exists = true;
            $instance->fill($result);
            $instance->syncOriginal();
            return $instance;
        }
        
        return null;
    }

    public static function all(): array
    {
        $instance = new static();
        $sql = "SELECT * FROM " . static::$table;
        $stmt = $instance->connection->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll();
        
        $models = [];
        foreach ($results as $result) {
            $model = new static($result);
            $model->exists = true;
            $model->syncOriginal();
            $models[] = $model;
        }
        
        return $models;
    }
}