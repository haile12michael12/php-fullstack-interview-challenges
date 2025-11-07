<?php

namespace App\Cache;

use App\Exception\CacheException;
use App\Exception\CacheMissException;

class CacheManager
{
    private array $adapters = [];
    private array $layers = [];

    public function addAdapter(string $name, CacheInterface $adapter, int $priority = 0): void
    {
        $this->adapters[$name] = $adapter;
        $this->layers[$name] = $priority;
        
        // Sort layers by priority (higher priority first)
        arsort($this->layers);
    }

    public function get(string $key, string $defaultLayer = null)
    {
        // If a specific layer is requested, try only that layer
        if ($defaultLayer !== null && isset($this->adapters[$defaultLayer])) {
            $value = $this->adapters[$defaultLayer]->get($key);
            if ($value !== null) {
                return $value;
            }
            throw new CacheMissException("Cache miss for key '$key' in layer '$defaultLayer'");
        }

        // Try all layers in priority order
        foreach ($this->layers as $layerName => $priority) {
            $value = $this->adapters[$layerName]->get($key);
            if ($value !== null) {
                // Promote to higher layers for faster access next time
                $this->promoteToHigherLayers($key, $value, $layerName);
                return $value;
            }
        }

        return null;
    }

    public function set(string $key, $value, int $ttl = 0, array $layers = null): bool
    {
        $layers = $layers ?? array_keys($this->adapters);
        $success = true;

        foreach ($layers as $layerName) {
            if (isset($this->adapters[$layerName])) {
                if (!$this->adapters[$layerName]->set($key, $value, $ttl)) {
                    $success = false;
                }
            }
        }

        return $success;
    }

    public function delete(string $key, array $layers = null): bool
    {
        $layers = $layers ?? array_keys($this->adapters);
        $success = true;

        foreach ($layers as $layerName) {
            if (isset($this->adapters[$layerName])) {
                if (!$this->adapters[$layerName]->delete($key)) {
                    $success = false;
                }
            }
        }

        return $success;
    }

    public function has(string $key, string $layer = null): bool
    {
        if ($layer !== null && isset($this->adapters[$layer])) {
            return $this->adapters[$layer]->has($key);
        }

        foreach ($this->layers as $layerName => $priority) {
            if ($this->adapters[$layerName]->has($key)) {
                return true;
            }
        }

        return false;
    }

    public function clear(array $layers = null): bool
    {
        $layers = $layers ?? array_keys($this->adapters);
        $success = true;

        foreach ($layers as $layerName) {
            if (isset($this->adapters[$layerName])) {
                if (!$this->adapters[$layerName]->clear()) {
                    $success = false;
                }
            }
        }

        return $success;
    }

    public function getStats(): array
    {
        $stats = [];
        
        foreach ($this->adapters as $name => $adapter) {
            if (method_exists($adapter, 'getStats')) {
                $stats[$name] = $adapter->getStats();
            }
        }
        
        return $stats;
    }

    private function promoteToHigherLayers(string $key, $value, string $currentLayer): void
    {
        $currentPriority = $this->layers[$currentLayer] ?? 0;
        
        foreach ($this->layers as $layerName => $priority) {
            // Only promote to layers with higher priority (faster access)
            if ($priority > $currentPriority && isset($this->adapters[$layerName])) {
                $this->adapters[$layerName]->set($key, $value);
            }
        }
    }
}