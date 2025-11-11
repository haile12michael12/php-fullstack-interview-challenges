<?php

namespace App\Database;

class Collection implements \IteratorAggregate, \Countable, \ArrayAccess
{
    protected $items = [];

    public function __construct($items = [])
    {
        $this->items = $items;
    }

    public static function make($items = [])
    {
        return new static($items);
    }

    public function all()
    {
        return $this->items;
    }

    public function first()
    {
        return $this->items[0] ?? null;
    }

    public function last()
    {
        return $this->items[count($this->items) - 1] ?? null;
    }

    public function count()
    {
        return count($this->items);
    }

    public function isEmpty()
    {
        return empty($this->items);
    }

    public function isNotEmpty()
    {
        return !$this->isEmpty();
    }

    public function map(callable $callback)
    {
        $keys = array_keys($this->items);
        $items = array_map($callback, $this->items, $keys);
        return new static(array_combine($keys, $items));
    }

    public function filter(callable $callback = null)
    {
        if ($callback) {
            return new static(array_filter($this->items, $callback, ARRAY_FILTER_USE_BOTH));
        }
        return new static(array_filter($this->items));
    }

    public function each(callable $callback)
    {
        foreach ($this->items as $key => $item) {
            if ($callback($item, $key) === false) {
                break;
            }
        }
        return $this;
    }

    public function pluck($value, $key = null)
    {
        return new static(array_column($this->items, $value, $key));
    }

    public function toArray()
    {
        return array_map(function ($value) {
            return $value instanceof Model ? $value->toArray() : $value;
        }, $this->items);
    }

    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }

    public function offsetExists($offset)
    {
        return isset($this->items[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->items[$offset];
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }

    public function __get($key)
    {
        return $this->items[$key] ?? null;
    }

    public function __set($key, $value)
    {
        $this->items[$key] = $value;
    }

    public function __isset($key)
    {
        return isset($this->items[$key]);
    }

    public function __unset($key)
    {
        unset($this->items[$key]);
    }

    public function __toString()
    {
        return $this->toJson();
    }
}