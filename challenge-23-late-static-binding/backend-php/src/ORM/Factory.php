<?php

namespace App\ORM;

class Factory
{
    protected static $instances = [];
    protected static $definitions = [];
    protected static $states = [];

    public static function define($class, callable $definition)
    {
        static::$definitions[$class] = $definition;
    }

    public static function state($class, $state, callable $definition)
    {
        if (!isset(static::$states[$class])) {
            static::$states[$class] = [];
        }
        
        static::$states[$class][$state] = $definition;
    }

    public static function create($class, array $attributes = [], array $states = [])
    {
        $definition = static::$definitions[$class] ?? function () {
            return [];
        };
        
        $instance = new $class();
        
        // Apply base definition
        $baseAttributes = $definition();
        $instance->fill($baseAttributes);
        
        // Apply states
        foreach ($states as $state) {
            if (isset(static::$states[$class][$state])) {
                $stateDefinition = static::$states[$class][$state];
                $stateAttributes = $stateDefinition();
                $instance->fill($stateAttributes);
            }
        }
        
        // Apply custom attributes
        $instance->fill($attributes);
        
        $instance->save();
        
        return $instance;
    }

    public static function make($class, array $attributes = [], array $states = [])
    {
        $definition = static::$definitions[$class] ?? function () {
            return [];
        };
        
        $instance = new $class();
        
        // Apply base definition
        $baseAttributes = $definition();
        $instance->fill($baseAttributes);
        
        // Apply states
        foreach ($states as $state) {
            if (isset(static::$states[$class][$state])) {
                $stateDefinition = static::$states[$class][$state];
                $stateAttributes = $stateDefinition();
                $instance->fill($stateAttributes);
            }
        }
        
        // Apply custom attributes
        $instance->fill($attributes);
        
        return $instance;
    }

    public static function times($class, $times, array $attributes = [], array $states = [])
    {
        $instances = [];
        
        for ($i = 0; $i < $times; $i++) {
            $instances[] = static::create($class, $attributes, $states);
        }
        
        return $instances;
    }
}