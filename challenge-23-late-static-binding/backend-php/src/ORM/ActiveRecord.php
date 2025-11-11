<?php

namespace App\ORM;

use App\Database\Model;
use App\Database\Collection;

class ActiveRecord extends Model
{
    protected static $modelRelations = [];
    protected $relations = [];

    public static function all()
    {
        $results = parent::all();
        return static::hydrate($results);
    }

    public static function find($id)
    {
        $result = parent::find($id);
        return $result ? new static($result) : null;
    }

    public static function where($column, $operator = null, $value = null)
    {
        return parent::where($column, $operator, $value);
    }

    public static function query()
    {
        return new Builder(static::class);
    }

    public static function hydrate($items)
    {
        return new Collection(array_map(function ($item) {
            return new static($item);
        }, $items));
    }

    public static function create($attributes = [])
    {
        $model = new static($attributes);
        $model->save();
        return $model;
    }

    public static function firstOrCreate($attributes, $values = [])
    {
        $instance = static::where(array_keys($attributes)[0], array_values($attributes)[0])->first();
        
        if ($instance) {
            return $instance;
        }
        
        return static::create(array_merge($attributes, $values));
    }

    public static function updateOrCreate($attributes, $values = [])
    {
        $instance = static::where(array_keys($attributes)[0], array_values($attributes)[0])->first();
        
        if ($instance) {
            $instance->fill($values);
            $instance->save();
            return $instance;
        }
        
        return static::create(array_merge($attributes, $values));
    }

    protected function getForeignKey()
    {
        return $this->snakeCase($this->classBasename(static::class)) . '_id';
    }

    protected function joiningTable($related)
    {
        $models = [
            $this->snakeCase($this->classBasename(static::class)),
            $this->snakeCase($this->classBasename($related))
        ];
        
        sort($models);
        
        return implode('_', $models);
    }

    protected function snakeCase($string)
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $string));
    }

    protected function classBasename($class)
    {
        return basename(str_replace('\\', '/', $class));
    }

    public function load($relations)
    {
        if (is_string($relations)) {
            $relations = func_get_args();
        }
        
        foreach ($relations as $name => $constraints) {
            if (is_numeric($name)) {
                $name = $constraints;
                $constraints = function () {};
            }
            
            $relation = $this->$name();
            $relation->addConstraints();
            $results = $relation->getResults();
            $this->relations[$name] = $results;
        }
        
        return $this;
    }

    public function getRelation($relation)
    {
        return $this->relations[$relation] ?? null;
    }

    public function __get($key)
    {
        if (array_key_exists($key, $this->relations)) {
            return $this->relations[$key];
        }
        
        if (method_exists($this, $key)) {
            return $this->relations[$key] = $this->$key()->getResults();
        }
        
        return parent::__get($key);
    }
}