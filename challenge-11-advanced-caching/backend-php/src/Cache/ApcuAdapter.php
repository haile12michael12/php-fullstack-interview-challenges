<?php

namespace App\Cache;

use App\Exception\CacheException;

class ApcuAdapter implements CacheInterface
{
    public function get(string $key)
    {
        $success = false;
        $value = apcu_fetch($key, $success);
        
        return $success ? $value : null;
    }

    public function set(string $key, $value, int $ttl = 0): bool
    {
        try {
            return apcu_store($key, $value, $ttl);
        } catch (\Exception $e) {
            throw new CacheException("Failed to set cache key '$key': " . $e->getMessage());
        }
    }

    public function delete(string $key): bool
    {
        return apcu_delete($key);
    }

    public function has(string $key): bool
    {
        return apcu_exists($key);
    }

    public function clear(): bool
    {
        return apcu_clear_cache();
    }

    public function getMultiple(array $keys): array
    {
        $values = apcu_fetch($keys);
        
        return is_array($values) ? $values : [];
    }

    public function setMultiple(array $values, int $ttl = 0): bool
    {
        try {
            return apcu_store($values, null, $ttl);
        } catch (\Exception $e) {
            throw new CacheException("Failed to set multiple cache keys: " . $e->getMessage());
        }
    }

    public function deleteMultiple(array $keys): bool
    {
        $result = true;
        
        foreach ($keys as $key) {
            if (!apcu_delete($key)) {
                $result = false;
            }
        }
        
        return $result;
    }
}