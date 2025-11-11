<?php

namespace App\Utils;

class Config
{
    protected static $instance = null;
    protected $config = [];

    protected function __construct($configFiles = [])
    {
        // Load default config
        $this->loadConfig(__DIR__ . '/../../config');
        
        // Load additional config files
        foreach ($configFiles as $file) {
            $this->loadConfigFile($file);
        }
    }

    public static function getInstance($configFiles = [])
    {
        if (static::$instance === null) {
            static::$instance = new static($configFiles);
        }
        return static::$instance;
    }

    protected function loadConfig($configDir)
    {
        if (!is_dir($configDir)) {
            return;
        }
        
        $files = glob($configDir . '/*.php');
        foreach ($files as $file) {
            $this->loadConfigFile($file);
        }
    }

    protected function loadConfigFile($file)
    {
        if (file_exists($file)) {
            $config = require $file;
            $key = basename($file, '.php');
            $this->config[$key] = $config;
        }
    }

    public static function get($key, $default = null)
    {
        $keys = explode('.', $key);
        $config = static::getInstance()->config;
        
        foreach ($keys as $k) {
            if (!isset($config[$k])) {
                return $default;
            }
            $config = $config[$k];
        }
        
        return $config;
    }

    public static function set($key, $value)
    {
        $keys = explode('.', $key);
        $config = &static::getInstance()->config;
        
        foreach ($keys as $k) {
            if (!isset($config[$k])) {
                $config[$k] = [];
            }
            $config = &$config[$k];
        }
        
        $config = $value;
    }
}