<?php

declare(strict_types=1);

namespace SharedBackend\Cache;

use SharedBackend\Core\Config;
use SharedBackend\Core\Logger;

class CacheManager
{
    private array $stores = [];
    private string $defaultStore;
    private Logger $logger;

    public function __construct(Config $config, Logger $logger)
    {
        $this->logger = $logger;
        $this->defaultStore = $config->get('cache.default', 'redis');
        
        // Initialize cache stores
        $stores = $config->get('cache.stores', []);
        
        foreach ($stores as $name => $storeConfig) {
            $this->stores[$name] = $this->createStore($storeConfig);
        }
    }

    private function createStore(array $config): CacheInterface
    {
        $driver = $config['driver'] ?? 'redis';
        
        return match ($driver) {
            'redis' => new RedisCache(new Config(['redis' => $config]), $this->logger),
            'memcached' => new MemcachedCache(new Config(['memcached' => $config]), $this->logger),
            default => throw new \InvalidArgumentException("Unsupported cache driver: {$driver}")
        };
    }

    public function store(string $name = null): CacheInterface
    {
        $name = $name ?? $this->defaultStore;
        
        if (!isset($this->stores[$name])) {
            throw new \InvalidArgumentException("Cache store '{$name}' is not configured");
        }
        
        return $this->stores[$name];
    }

    public function get(string $key, mixed $default = null, string $store = null): mixed
    {
        return $this->store($store)->get($key, $default);
    }

    public function set(string $key, mixed $value, ?int $ttl = null, string $store = null): bool
    {
        return $this->store($store)->set($key, $value, $ttl);
    }

    public function delete(string $key, string $store = null): bool
    {
        return $this->store($store)->delete($key);
    }

    public function has(string $key, string $store = null): bool
    {
        return $this->store($store)->has($key);
    }

    public function clear(string $store = null): bool
    {
        return $this->store($store)->clear();
    }
}