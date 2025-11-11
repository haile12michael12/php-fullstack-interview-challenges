<?php

namespace App\Database;

abstract class Model
{
    protected static $table;
    protected static $fillable = [];
    protected static $hidden = [];
    protected static $primaryKey = 'id';
    protected static $timestamps = true;
    
    protected $attributes = [];
    protected $original = [];
    protected $exists = false;

    public function __construct($attributes = [])
    {
        $this->fill($attributes);
        $this->syncOriginal();
    }

    public static function getTable()
    {
        return static::$table ?: strtolower(class_basename(static::class)) . 's';
    }

    public static function getKeyName()
    {
        return static::$primaryKey;
    }

    public static function all()
    {
        return static::query()->get();
    }

    public static function find($id)
    {
        return static::query()->find($id);
    }

    public static function where($column, $operator = null, $value = null)
    {
        return static::query()->where($column, $operator, $value);
    }

    public static function query()
    {
        return new QueryBuilder(static::getTable());
    }

    public function fill($attributes)
    {
        foreach ($attributes as $key => $value) {
            if ($this->isFillable($key)) {
                $this->setAttribute($key, $value);
            }
        }
        return $this;
    }

    public function isFillable($key)
    {
        return empty(static::$fillable) || in_array($key, static::$fillable);
    }

    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
        return $this;
    }

    public function getAttribute($key)
    {
        return $this->attributes[$key] ?? null;
    }

    public function syncOriginal()
    {
        $this->original = $this->attributes;
        return $this;
    }

    public function save()
    {
        if ($this->exists) {
            return $this->performUpdate();
        } else {
            return $this->performInsert();
        }
    }

    protected function performInsert()
    {
        $attributes = $this->getAttributes();
        
        if (static::$timestamps) {
            $attributes['created_at'] = date('Y-m-d H:i:s');
            $attributes['updated_at'] = date('Y-m-d H:i:s');
        }
        
        $id = static::query()->insert($attributes);
        
        if ($id) {
            $this->exists = true;
            $this->setAttribute(static::$primaryKey, $id);
            $this->syncOriginal();
            return true;
        }
        
        return false;
    }

    protected function performUpdate()
    {
        if (!$this->isDirty()) {
            return true;
        }
        
        $attributes = $this->getDirty();
        
        if (static::$timestamps) {
            $attributes['updated_at'] = date('Y-m-d H:i:s');
        }
        
        $result = static::query()->where(static::$primaryKey, $this->getAttribute(static::$primaryKey))->update($attributes);
        
        if ($result) {
            $this->syncOriginal();
            return true;
        }
        
        return false;
    }

    public function delete()
    {
        if (!$this->exists) {
            return false;
        }
        
        $result = static::query()->where(static::$primaryKey, $this->getAttribute(static::$primaryKey))->delete();
        
        if ($result) {
            $this->exists = false;
            return true;
        }
        
        return false;
    }

    public function isDirty($attribute = null)
    {
        if ($attribute) {
            return $this->getAttribute($attribute) !== ($this->original[$attribute] ?? null);
        }
        
        return $this->attributes !== $this->original;
    }

    public function getDirty()
    {
        $dirty = [];
        foreach ($this->attributes as $key => $value) {
            if (!array_key_exists($key, $this->original) || $value !== $this->original[$key]) {
                $dirty[$key] = $value;
            }
        }
        return $dirty;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function toArray()
    {
        return $this->attributes;
    }

    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    public function __set($key, $value)
    {
        return $this->setAttribute($key, $value);
    }

    public function __isset($key)
    {
        return isset($this->attributes[$key]);
    }

    public function __unset($key)
    {
        unset($this->attributes[$key]);
    }
}