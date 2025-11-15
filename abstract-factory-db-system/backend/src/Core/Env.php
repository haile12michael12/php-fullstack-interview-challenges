<?php

namespace Core;

class Env
{
    public static function get($key, $default = null)
    {
        $value = getenv($key);
        
        if ($value === false) {
            return $default;
        }
        
        return $value;
    }
    
    public static function set($key, $value)
    {
        putenv("{$key}={$value}");
    }
    
    public static function has($key)
    {
        return getenv($key) !== false;
    }
}