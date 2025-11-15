<?php

namespace Core;

class Config
{
    private static $instance = null;
    private $config = [];

    private function __construct()
    {
        // Load configuration from .env file
        $this->loadEnv();
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }

    private function loadEnv()
    {
        $envFile = __DIR__ . '/../../.env';
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
                    list($key, $value) = explode('=', $line, 2);
                    $this->config[trim($key)] = trim($value);
                }
            }
        }
    }

    public function get($key, $default = null)
    {
        return $this->config[$key] ?? $default;
    }

    public function set($key, $value)
    {
        $this->config[$key] = $value;
    }
}