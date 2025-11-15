<?php

namespace App\Application;

class Configuration
{
    protected static $instance = null;
    protected $config = [];

    protected function __construct()
    {
        // Load default configuration
        $this->loadDefaultConfig();
        
        // Load environment-specific configuration
        $this->loadEnvironmentConfig();
    }

    public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    protected function loadDefaultConfig()
    {
        $this->config = [
            'database' => [
                'default' => 'mysql',
                'connections' => [
                    'mysql' => [
                        'host' => 'localhost',
                        'port' => 3306,
                        'database' => 'abstract_factory',
                        'username' => 'root',
                        'password' => '',
                        'charset' => 'utf8mb4',
                    ],
                    'postgresql' => [
                        'host' => 'localhost',
                        'port' => 5432,
                        'database' => 'abstract_factory',
                        'username' => 'postgres',
                        'password' => '',
                    ],
                    'sqlite' => [
                        'database' => ':memory:',
                    ],
                ],
            ],
            'redis' => [
                'host' => 'localhost',
                'port' => 6379,
                'timeout' => 0,
            ],
            'jwt' => [
                'secret' => 'your-secret-key',
                'ttl' => 3600,
            ],
            'rate_limit' => [
                'max_requests' => 60,
                'window' => 60,
            ],
        ];
    }

    protected function loadEnvironmentConfig()
    {
        // Load configuration from environment variables
        $envConfig = [
            'database' => [
                'connections' => [
                    'mysql' => [
                        'host' => $_ENV['DB_HOST'] ?? $this->config['database']['connections']['mysql']['host'],
                        'port' => $_ENV['DB_PORT'] ?? $this->config['database']['connections']['mysql']['port'],
                        'database' => $_ENV['DB_DATABASE'] ?? $this->config['database']['connections']['mysql']['database'],
                        'username' => $_ENV['DB_USERNAME'] ?? $this->config['database']['connections']['mysql']['username'],
                        'password' => $_ENV['DB_PASSWORD'] ?? $this->config['database']['connections']['mysql']['password'],
                    ],
                    'postgresql' => [
                        'host' => $_ENV['PG_HOST'] ?? $this->config['database']['connections']['postgresql']['host'],
                        'port' => $_ENV['PG_PORT'] ?? $this->config['database']['connections']['postgresql']['port'],
                        'database' => $_ENV['PG_DATABASE'] ?? $this->config['database']['connections']['postgresql']['database'],
                        'username' => $_ENV['PG_USERNAME'] ?? $this->config['database']['connections']['postgresql']['username'],
                        'password' => $_ENV['PG_PASSWORD'] ?? $this->config['database']['connections']['postgresql']['password'],
                    ],
                ],
            ],
            'redis' => [
                'host' => $_ENV['REDIS_HOST'] ?? $this->config['redis']['host'],
                'port' => $_ENV['REDIS_PORT'] ?? $this->config['redis']['port'],
            ],
            'jwt' => [
                'secret' => $_ENV['JWT_SECRET'] ?? $this->config['jwt']['secret'],
            ],
        ];

        // Merge environment config with default config
        $this->config = array_replace_recursive($this->config, $envConfig);
    }

    public static function get($key, $default = null)
    {
        $instance = static::getInstance();
        $keys = explode('.', $key);
        $config = $instance->config;

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
        $instance = static::getInstance();
        $keys = explode('.', $key);
        $config = &$instance->config;

        foreach ($keys as $k) {
            if (!isset($config[$k])) {
                $config[$k] = [];
            }
            $config = &$config[$k];
        }

        $config = $value;
    }
}