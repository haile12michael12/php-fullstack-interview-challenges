<?php

namespace App\ORM;

use App\Database\QueryBuilder;

class Builder extends QueryBuilder
{
    protected $model;
    protected $eagerLoad = [];

    public function __construct($model)
    {
        $this->model = $model;
        $table = (new $model)->getTable();
        parent::__construct($table);
    }

    public static function forModel($model)
    {
        return new static($model);
    }

    public function with($relations)
    {
        $this->eagerLoad = array_merge($this->eagerLoad, is_string($relations) ? func_get_args() : $relations);
        return $this;
    }

    public function get()
    {
        $results = parent::get();
        
        if (!empty($this->eagerLoad)) {
            $results = $this->eagerLoadRelations($results);
        }
        
        return $this->model::hydrate($results);
    }

    public function first()
    {
        $result = parent::first();
        return $result ? new $this->model($result) : null;
    }

    public function find($id)
    {
        return $this->where((new $this->model)->getKeyName(), $id)->first();
    }

    public function create($attributes)
    {
        $instance = new $this->model($attributes);
        $instance->save();
        return $instance;
    }

    protected function eagerLoadRelations($results)
    {
        // This is a simplified implementation
        // In a real implementation, this would handle complex eager loading
        return $results;
    }
}