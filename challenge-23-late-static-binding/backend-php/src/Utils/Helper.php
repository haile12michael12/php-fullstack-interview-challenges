<?php

namespace App\Utils;

class Helper
{
    public static function snakeCase($string)
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $string));
    }

    public static function camelCase($string)
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $string))));
    }

    public static function studlyCase($string)
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));
    }

    public static function classBasename($class)
    {
        return basename(str_replace('\\', '/', $class));
    }

    public static function arrayGet($array, $key, $default = null)
    {
        return $array[$key] ?? $default;
    }

    public static function arraySet(&$array, $key, $value)
    {
        $array[$key] = $value;
    }

    public static function arrayHas($array, $key)
    {
        return isset($array[$key]);
    }

    public static function arrayForget(&$array, $key)
    {
        unset($array[$key]);
    }
}