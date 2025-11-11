<?php

namespace App\Traits;

trait CacheableTrait
{
    protected array $cache = [];
    protected int $cacheTtl = 3600; // 1 hour default

    public function setCacheTtl(int $ttl): void
    {
        $this->cacheTtl = $ttl;
    }

    public function getFromCache(string $key, callable $callback)
    {
        $cacheKey = $this->generateCacheKey($key);
        
        // Check if item exists in cache and is still valid
        if (isset($this->cache[$cacheKey])) {
            $cachedItem = $this->cache[$cacheKey];
            if (time() < $cachedItem['expires']) {
                return $cachedItem['data'];
            }
            // Remove expired item
            unset($this->cache[$cacheKey]);
        }
        
        // Generate new data and cache it
        $data = $callback();
        $this->cache[$cacheKey] = [
            'data' => $data,
            'expires' => time() + $this->cacheTtl
        ];
        
        return $data;
    }

    public function clearCache(string $key = null): void
    {
        if ($key === null) {
            $this->cache = [];
        } else {
            $cacheKey = $this->generateCacheKey($key);
            unset($this->cache[$cacheKey]);
        }
    }

    public function getCacheStats(): array
    {
        return [
            'count' => count($this->cache),
            'keys' => array_keys($this->cache)
        ];
    }

    protected function generateCacheKey(string $key): string
    {
        return md5($key);
    }
}