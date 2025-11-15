<?php

namespace Core;

class Autoloader
{
    public static function register()
    {
        spl_autoload_register([self::class, 'autoload']);
    }

    public static function autoload($class)
    {
        // Convert namespace to file path
        $file = __DIR__ . '/../../' . str_replace('\\', '/', $class) . '.php';
        
        if (file_exists($file)) {
            require_once $file;
        }
    }
}