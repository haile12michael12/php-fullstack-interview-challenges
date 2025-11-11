<?php

namespace App\Utils;

class Env
{
    protected static $instance = null;
    protected $env = [];

    protected function __construct($envFile = null)
    {
        $this->loadEnv($envFile ?: __DIR__ . '/../../.env');
    }

    public static function getInstance($envFile = null)
    {
        if (static::$instance === null) {
            static::$instance = new static($envFile);
        }
        return static::$instance;
    }

    protected function loadEnv($envFile)
    {
        if (!file_exists($envFile)) {
            return;
        }
        
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            // Skip comments
            if (strpos($line, '#') === 0) {
                continue;
            }
            
            // Parse key=value pairs
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);
                
                // Remove quotes if present
                if (strpos($value, '"') === 0 && strrpos($value, '"') === strlen($value) - 1) {
                    $value = substr($value, 1, -1);
                }
                
                $this->env[$key] = $value;
            }
        }
    }

    public static function get($key, $default = null)
    {
        return static::getInstance()->env[$key] ?? $default;
    }

    public static function set($key, $value)
    {
        static::getInstance()->env[$key] = $value;
    }
}