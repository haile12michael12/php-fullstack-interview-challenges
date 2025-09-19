<?php

declare(strict_types=1);

namespace SharedBackend\Core;

use Redis;
use Predis\Client as PredisClient;
use SharedBackend\Core\Exceptions\CacheException;

/**
 * Advanced caching system with multiple backends and cache tagging
 */
class Cache
{
    private mixed $store;
    private string $driver;
    private Config $config;
    private Logger $logger;
    private array $tags = [];

    public function __construct(Config $config, Logger $logger)
    {
        $this->config = $config;
        $this->logger = $logger;
        $this->driver = $config->get('cache.default', 'file');
        $this->initializeStore();
    }

    public function get(string $key, mixed $default = null): mixed
    {
        try {
            $value = $this->performGet($key);
            
            if ($value === null) {
                $this->logger->debug('Cache miss', ['key' => $key]);
                return $default;
            }

            $this->logger->debug('Cache hit', ['key' => $key]);
            return $value;
        } catch (\Exception $e) {
            $this->logger->error('Cache get failed', [
                'key' => $key,
                'error' => $e->getMessage()
            ]);
            return $default;
        }
    }

    public function set(string $key, mixed $value, int $ttl = 3600): bool
    {
        try {
            $result = $this->performSet($key, $value, $ttl);
            
            $this->logger->debug('Cache set', [
                'key' => $key,
                'ttl' => $ttl,
                'success' => $result
            ]);

            return $result;
        } catch (\Exception $e) {
            $this->logger->error('Cache set failed', [
                'key' => $key,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    public function delete(string $key): bool
    {
        try {
            $result = $this->performDelete($key);
            
            $this->logger->debug('Cache delete', [
                'key' => $key,
                'success' => $result
            ]);

            return $result;
        } catch (\Exception $e) {
            $this->logger->error('Cache delete failed', [
                'key' => $key,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    public function has(string $key): bool
    {
        return $this->get($key) !== null;
    }

    public function flush(): bool
    {
        try {
            $result = $this->performFlush();
            
            $this->logger->info('Cache flushed');
            return $result;
        } catch (\Exception $e) {
            $this->logger->error('Cache flush failed', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    public function remember(string $key, callable $callback, int $ttl = 3600): mixed
    {
        $value = $this->get($key);
        
        if ($value !== null) {
            return $value;
        }

        $value = $callback();
        $this->set($key, $value, $ttl);
        
        return $value;
    }

    public function increment(string $key, int $value = 1): int
    {
        try {
            if ($this->driver === 'redis') {
                return $this->store->incrBy($key, $value);
            }

            $current = (int)$this->get($key, 0);
            $newValue = $current + $value;
            $this->set($key, $newValue);
            
            return $newValue;
        } catch (\Exception $e) {
            $this->logger->error('Cache increment failed', [
                'key' => $key,
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }

    public function decrement(string $key, int $value = 1): int
    {
        return $this->increment($key, -$value);
    }

    public function tags(array $tags): self
    {
        $newCache = clone $this;
        $newCache->tags = array_merge($this->tags, $tags);
        return $newCache;
    }

    public function forgetTag(string $tag): bool
    {
        try {
            if ($this->driver === 'redis') {
                $pattern = "tag:{$tag}:*";
                $keys = $this->store->keys($pattern);
                if (!empty($keys)) {
                    $this->store->del($keys);
                }
                return true;
            }

            // For file cache, we need to implement tag tracking
            $this->logger->warning('Tag-based cache invalidation not fully implemented for file driver');
            return false;
        } catch (\Exception $e) {
            $this->logger->error('Cache tag forget failed', [
                'tag' => $tag,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    private function initializeStore(): void
    {
        switch ($this->driver) {
            case 'redis':
                $this->initializeRedis();
                break;
            case 'file':
                $this->initializeFile();
                break;
            default:
                throw new CacheException("Unsupported cache driver: {$this->driver}");
        }
    }

    private function initializeRedis(): void
    {
        $config = $this->config->get('cache.stores.redis');
        
        if (extension_loaded('redis')) {
            $this->store = new Redis();
            $this->store->connect($config['host'], $config['port']);
            
            if (!empty($config['password'])) {
                $this->store->auth($config['password']);
            }
            
            if (isset($config['database'])) {
                $this->store->select($config['database']);
            }
        } else {
            $this->store = new PredisClient([
                'scheme' => 'tcp',
                'host' => $config['host'],
                'port' => $config['port'],
                'password' => $config['password'] ?? null,
                'database' => $config['database'] ?? 0,
            ]);
        }
    }

    private function initializeFile(): void
    {
        $path = $this->config->get('cache.stores.file.path', '/tmp/cache');
        
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }

        $this->store = $path;
    }

    private function performGet(string $key): mixed
    {
        switch ($this->driver) {
            case 'redis':
                $value = $this->store->get($key);
                return $value !== false ? unserialize($value) : null;
                
            case 'file':
                $file = $this->store . '/' . md5($key) . '.cache';
                if (!file_exists($file)) {
                    return null;
                }
                
                $data = unserialize(file_get_contents($file));
                if ($data === false || $data['expires'] < time()) {
                    unlink($file);
                    return null;
                }
                
                return $data['value'];
                
            default:
                return null;
        }
    }

    private function performSet(string $key, mixed $value, int $ttl): bool
    {
        switch ($this->driver) {
            case 'redis':
                return $this->store->setex($key, $ttl, serialize($value));
                
            case 'file':
                $file = $this->store . '/' . md5($key) . '.cache';
                $data = [
                    'value' => $value,
                    'expires' => time() + $ttl,
                    'tags' => $this->tags
                ];
                return file_put_contents($file, serialize($data)) !== false;
                
            default:
                return false;
        }
    }

    private function performDelete(string $key): bool
    {
        switch ($this->driver) {
            case 'redis':
                return $this->store->del($key) > 0;
                
            case 'file':
                $file = $this->store . '/' . md5($key) . '.cache';
                return !file_exists($file) || unlink($file);
                
            default:
                return false;
        }
    }

    private function performFlush(): bool
    {
        switch ($this->driver) {
            case 'redis':
                return $this->store->flushdb();
                
            case 'file':
                $files = glob($this->store . '/*.cache');
                foreach ($files as $file) {
                    unlink($file);
                }
                return true;
                
            default:
                return false;
        }
    }
}
