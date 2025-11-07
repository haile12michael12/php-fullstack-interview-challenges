<?php

namespace App\Cache;

use Redis;
use App\Exception\CacheException;

class RedisAdapter implements CacheInterface
{
    private Redis $redis;

    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }

    public function get(string $key)
    {
        $value = $this->redis->get($key);
        
        if ($value === false) {
            return null;
        }
        
        return unserialize($value);
    }

    public function set(string $key, $value, int $ttl = 0): bool
    {
        try {
            $serializedValue = serialize($value);
            
            if ($ttl > 0) {
                return $this->redis->setex($key, $ttl, $serializedValue);
            }
            
            return $this->redis->set($key, $serializedValue);
        } catch (\Exception $e) {
            throw new CacheException("Failed to set cache key '$key': " . $e->getMessage());
        }
    }

    public function delete(string $key): bool
    {
        return $this->redis->del($key) > 0;
    }

    public function has(string $key): bool
    {
        return $this->redis->exists($key) > 0;
    }

    public function clear(): bool
    {
        return $this->redis->flushDB();
    }

    public function getMultiple(array $keys): array
    {
        $values = $this->redis->mget($keys);
        $result = [];
        
        foreach ($keys as $index => $key) {
            if ($values[$index] !== false) {
                $result[$key] = unserialize($values[$index]);
            }
        }
        
        return $result;
    }

    public function setMultiple(array $values, int $ttl = 0): bool
    {
        try {
            $serializedValues = [];
            
            foreach ($values as $key => $value) {
                $serializedValues[$key] = serialize($value);
            }
            
            if ($ttl > 0) {
                // Redis doesn't support TTL for multiple keys directly
                foreach ($serializedValues as $key => $value) {
                    $this->redis->setex($key, $ttl, $value);
                }
                return true;
            }
            
            return $this->redis->mset($serializedValues);
        } catch (\Exception $e) {
            throw new CacheException("Failed to set multiple cache keys: " . $e->getMessage());
        }
    }

    public function deleteMultiple(array $keys): bool
    {
        return $this->redis->del($keys) > 0;
    }
}