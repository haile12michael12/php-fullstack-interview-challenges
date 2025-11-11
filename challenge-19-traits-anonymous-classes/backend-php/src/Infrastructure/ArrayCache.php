<?php

namespace App\Infrastructure;

use App\Contracts\CacheInterface;
use App\Traits\SerializableTrait;

class ArrayCache implements CacheInterface
{
    use SerializableTrait;
    
    protected array $storage = [];
    
    public function get(string $key)
    {
        if (!$this->has($key)) {
            return null;
        }
        
        $item = $this->storage[$key];
        if (time() > $item['expires']) {
            $this->delete($key);
            return null;
        }
        
        return $item['data'];
    }
    
    public function set(string $key, $value, int $ttl = 3600): bool
    {
        $this->storage[$key] = [
            'data' => $value,
            'expires' => time() + $ttl
        ];
        
        return true;
    }
    
    public function has(string $key): bool
    {
        return isset($this->storage[$key]);
    }
    
    public function delete(string $key): bool
    {
        if ($this->has($key)) {
            unset($this->storage[$key]);
            return true;
        }
        
        return false;
    }
    
    public function clear(): bool
    {
        $this->storage = [];
        return true;
    }
    
    public function getStats(): array
    {
        return [
            'count' => count($this->storage),
            'keys' => array_keys($this->storage)
        ];
    }
}