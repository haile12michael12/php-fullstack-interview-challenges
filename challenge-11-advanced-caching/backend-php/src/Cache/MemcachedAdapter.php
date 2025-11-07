<?php

namespace App\Cache;

use Memcached;
use App\Exception\CacheException;

class MemcachedAdapter implements CacheInterface
{
    private Memcached $memcached;

    public function __construct(Memcached $memcached)
    {
        $this->memcached = $memcached;
    }

    public function get(string $key)
    {
        $value = $this->memcached->get($key);
        
        if ($this->memcached->getResultCode() === Memcached::RES_NOTFOUND) {
            return null;
        }
        
        return $value;
    }

    public function set(string $key, $value, int $ttl = 0): bool
    {
        try {
            return $this->memcached->set($key, $value, $ttl);
        } catch (\Exception $e) {
            throw new CacheException("Failed to set cache key '$key': " . $e->getMessage());
        }
    }

    public function delete(string $key): bool
    {
        return $this->memcached->delete($key);
    }

    public function has(string $key): bool
    {
        $this->memcached->get($key);
        return $this->memcached->getResultCode() === Memcached::RES_SUCCESS;
    }

    public function clear(): bool
    {
        return $this->memcached->flush();
    }

    public function getMultiple(array $keys): array
    {
        $values = $this->memcached->getMulti($keys);
        
        if ($values === false) {
            return [];
        }
        
        return $values;
    }

    public function setMultiple(array $values, int $ttl = 0): bool
    {
        try {
            return $this->memcached->setMulti($values, $ttl);
        } catch (\Exception $e) {
            throw new CacheException("Failed to set multiple cache keys: " . $e->getMessage());
        }
    }

    public function deleteMultiple(array $keys): bool
    {
        return $this->memcached->deleteMulti($keys);
    }
}