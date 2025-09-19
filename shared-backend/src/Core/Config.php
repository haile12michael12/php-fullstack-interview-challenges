<?php

declare(strict_types=1);

namespace SharedBackend\Core;

use Dotenv\Dotenv;

/**
 * Advanced configuration management with environment variable support
 */
class Config
{
    private array $config = [];
    private array $cache = [];
    private string $basePath;

    public function __construct(string $basePath = null)
    {
        $this->basePath = $basePath ?? getcwd();
        $this->loadEnvironment();
        $this->loadConfigFiles();
    }

    public function get(string $key, mixed $default = null): mixed
    {
        if (isset($this->cache[$key])) {
            return $this->cache[$key];
        }

        $value = $this->getNestedValue($this->config, $key) ?? $default;
        $this->cache[$key] = $value;
        
        return $value;
    }

    public function set(string $key, mixed $value): void
    {
        $this->setNestedValue($this->config, $key, $value);
        unset($this->cache[$key]);
    }

    public function has(string $key): bool
    {
        return $this->getNestedValue($this->config, $key) !== null;
    }

    public function all(): array
    {
        return $this->config;
    }

    public function isProduction(): bool
    {
        return $this->get('app.env', 'local') === 'production';
    }

    public function isDevelopment(): bool
    {
        return $this->get('app.env', 'local') === 'development';
    }

    public function isTesting(): bool
    {
        return $this->get('app.env', 'local') === 'testing';
    }

    private function loadEnvironment(): void
    {
        $envFile = $this->basePath . '/.env';
        if (file_exists($envFile)) {
            $dotenv = Dotenv::createImmutable($this->basePath);
            $dotenv->load();
        }
    }

    private function loadConfigFiles(): void
    {
        $configPath = $this->basePath . '/config';
        if (!is_dir($configPath)) {
            $this->config = $this->getDefaultConfig();
            return;
        }

        $files = glob($configPath . '/*.php');
        foreach ($files as $file) {
            $key = basename($file, '.php');
            $this->config[$key] = require $file;
        }
    }

    private function getNestedValue(array $array, string $key): mixed
    {
        $keys = explode('.', $key);
        $value = $array;

        foreach ($keys as $k) {
            if (!is_array($value) || !array_key_exists($k, $value)) {
                return null;
            }
            $value = $value[$k];
        }

        return $value;
    }

    private function setNestedValue(array &$array, string $key, mixed $value): void
    {
        $keys = explode('.', $key);
        $current = &$array;

        foreach ($keys as $k) {
            if (!isset($current[$k]) || !is_array($current[$k])) {
                $current[$k] = [];
            }
            $current = &$current[$k];
        }

        $current = $value;
    }

    private function getDefaultConfig(): array
    {
        return [
            'app' => [
                'name' => $_ENV['APP_NAME'] ?? 'PHP Fullstack Challenge',
                'env' => $_ENV['APP_ENV'] ?? 'local',
                'debug' => filter_var($_ENV['APP_DEBUG'] ?? 'true', FILTER_VALIDATE_BOOLEAN),
                'url' => $_ENV['APP_URL'] ?? 'http://localhost',
                'timezone' => $_ENV['APP_TIMEZONE'] ?? 'UTC',
                'locale' => $_ENV['APP_LOCALE'] ?? 'en',
                'fallback_locale' => $_ENV['APP_FALLBACK_LOCALE'] ?? 'en',
            ],
            'database' => [
                'default' => $_ENV['DB_CONNECTION'] ?? 'mysql',
                'connections' => [
                    'mysql' => [
                        'driver' => 'mysql',
                        'host' => $_ENV['DB_HOST'] ?? '127.0.0.1',
                        'port' => $_ENV['DB_PORT'] ?? '3306',
                        'database' => $_ENV['DB_DATABASE'] ?? 'forge',
                        'username' => $_ENV['DB_USERNAME'] ?? 'forge',
                        'password' => $_ENV['DB_PASSWORD'] ?? '',
                        'charset' => 'utf8mb4',
                        'collation' => 'utf8mb4_unicode_ci',
                        'prefix' => '',
                        'strict' => true,
                        'engine' => null,
                    ],
                    'sqlite' => [
                        'driver' => 'sqlite',
                        'database' => $_ENV['DB_DATABASE'] ?? ':memory:',
                        'prefix' => '',
                    ],
                    'pgsql' => [
                        'driver' => 'pgsql',
                        'host' => $_ENV['DB_HOST'] ?? '127.0.0.1',
                        'port' => $_ENV['DB_PORT'] ?? '5432',
                        'database' => $_ENV['DB_DATABASE'] ?? 'forge',
                        'username' => $_ENV['DB_USERNAME'] ?? 'forge',
                        'password' => $_ENV['DB_PASSWORD'] ?? '',
                        'charset' => 'utf8',
                        'prefix' => '',
                        'schema' => 'public',
                        'sslmode' => 'prefer',
                    ],
                ],
            ],
            'cache' => [
                'default' => $_ENV['CACHE_DRIVER'] ?? 'file',
                'stores' => [
                    'file' => [
                        'driver' => 'file',
                        'path' => $_ENV['CACHE_PATH'] ?? '/tmp/cache',
                    ],
                    'redis' => [
                        'driver' => 'redis',
                        'host' => $_ENV['REDIS_HOST'] ?? '127.0.0.1',
                        'port' => $_ENV['REDIS_PORT'] ?? '6379',
                        'password' => $_ENV['REDIS_PASSWORD'] ?? null,
                        'database' => $_ENV['REDIS_DATABASE'] ?? 0,
                    ],
                ],
            ],
            'logging' => [
                'default' => $_ENV['LOG_CHANNEL'] ?? 'stack',
                'channels' => [
                    'stack' => [
                        'driver' => 'stack',
                        'channels' => ['single', 'daily'],
                    ],
                    'single' => [
                        'driver' => 'single',
                        'path' => $_ENV['LOG_PATH'] ?? '/tmp/logs/app.log',
                        'level' => $_ENV['LOG_LEVEL'] ?? 'debug',
                    ],
                    'daily' => [
                        'driver' => 'daily',
                        'path' => $_ENV['LOG_PATH'] ?? '/tmp/logs/app.log',
                        'level' => $_ENV['LOG_LEVEL'] ?? 'debug',
                        'days' => 14,
                    ],
                ],
            ],
            'session' => [
                'driver' => $_ENV['SESSION_DRIVER'] ?? 'file',
                'lifetime' => $_ENV['SESSION_LIFETIME'] ?? 120,
                'expire_on_close' => false,
                'encrypt' => false,
                'files' => $_ENV['SESSION_PATH'] ?? '/tmp/sessions',
                'connection' => null,
                'table' => 'sessions',
                'store' => null,
                'lottery' => [2, 100],
                'cookie' => $_ENV['SESSION_COOKIE'] ?? 'php_challenge_session',
                'path' => '/',
                'domain' => $_ENV['SESSION_DOMAIN'] ?? null,
                'secure' => filter_var($_ENV['SESSION_SECURE'] ?? 'false', FILTER_VALIDATE_BOOLEAN),
                'http_only' => true,
                'same_site' => 'lax',
            ],
            'cors' => [
                'allowed_origins' => explode(',', $_ENV['CORS_ALLOWED_ORIGINS'] ?? '*'),
                'allowed_methods' => explode(',', $_ENV['CORS_ALLOWED_METHODS'] ?? 'GET,POST,PUT,DELETE,OPTIONS'),
                'allowed_headers' => explode(',', $_ENV['CORS_ALLOWED_HEADERS'] ?? '*'),
                'exposed_headers' => explode(',', $_ENV['CORS_EXPOSED_HEADERS'] ?? ''),
                'max_age' => (int)($_ENV['CORS_MAX_AGE'] ?? 86400),
                'supports_credentials' => filter_var($_ENV['CORS_SUPPORTS_CREDENTIALS'] ?? 'false', FILTER_VALIDATE_BOOLEAN),
            ],
        ];
    }
}
