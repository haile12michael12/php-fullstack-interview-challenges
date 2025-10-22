<?php

declare(strict_types=1);

namespace SharedBackend\Cache;

use Predis\Client;
use SharedBackend\Core\Config;
use SharedBackend\Core\Logger;

class RedisCache implements CacheInterface
{
    private Client $client;
    private Logger $logger;

    public function __construct(Config $config, Logger $logger)
    {
        $this->logger = $logger;
        
        $redisConfig = [
            'scheme' => $config->get('redis.scheme', 'tcp'),
            'host' => $config->get('redis.host', '127.0.0.1'),
            'port' => $config->get('redis.port', 6379),
            'database' => $config->get('redis.database', 0),
        ];

        if ($config->get('redis.password')) {
            $redisConfig['password'] = $config->get('redis.password');
        }

        $this->client = new Client($redisConfig);
        
        $this->logger->info('Redis cache initialized', $redisConfig);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        try {
            $value = $this->client->get($key);
            
            if ($value === null) {
                return $default;
            }
            
            return unserialize($value);
        } catch (\Exception $e) {
            $this->logger->error('Redis get failed', [
                'key' => $key,
                'error' => $e->getMessage()
            ]);
            
            return $default;
        }
    }

    public function set(string $key, mixed $value, ?int $ttl = null): bool
    {
        try {
            $serializedValue = serialize($value);
            
            if ($ttl !== null) {
                $result = $this->client->setex($key, $ttl, $serializedValue);
            } else {
                $result = $this->client->set($key, $serializedValue);
            }
            
            return $result;
        } catch (\Exception $e) {
            $this->logger->error('Redis set failed', [
                'key' => $key,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    public function delete(string $key): bool
    {
        try {
            return (bool) $this->client->del($key);
        } catch (\Exception $e) {
            $this->logger->error('Redis delete failed', [
                'key' => $key,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    public function has(string $key): bool
    {
        try {
            return (bool) $this->client->exists($key);
        } catch (\Exception $e) {
            $this->logger->error('Redis exists check failed', [
                'key' => $key,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    public function clear(): bool
    {
        try {
            return (bool) $this->client->flushdb();
        } catch (\Exception $e) {
            $this->logger->error('Redis flush failed', [
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    public function getMultiple(array $keys, mixed $default = null): array
    {
        try {
            $values = $this->client->mget($keys);
            $result = [];
            
            foreach ($keys as $index => $key) {
                $value = $values[$index];
                
                if ($value === null) {
                    $result[$key] = $default;
                } else {
                    $result[$key] = unserialize($value);
                }
            }
            
            return $result;
        } catch (\Exception $e) {
            $this->logger->error('Redis mget failed', [
                'keys' => $keys,
                'error' => $e->getMessage()
            ]);
            
            return array_fill_keys($keys, $default);
        }
    }

    public function setMultiple(array $values, ?int $ttl = null): bool
    {
        try {
            if ($ttl !== null) {
                // For TTL with multiple values, we need to set them individually
                foreach ($values as $key => $value) {
                    $this->set($key, $value, $ttl);
                }
                return true;
            }
            
            $serializedValues = [];
            foreach ($values as $key => $value) {
                $serializedValues[$key] = serialize($value);
            }
            
            return (bool) $this->client->mset($serializedValues);
        } catch (\Exception $e) {
            $this->logger->error('Redis mset failed', [
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    public function deleteMultiple(array $keys): bool
    {
        try {
            return (bool) $this->client->del($keys);
        } catch (\Exception $e) {
            $this->logger->error('Redis multi delete failed', [
                'keys' => $keys,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Get cache statistics
     *
     * @return array
     */
    public function getStats(): array
    {
        try {
            return $this->client->info();
        } catch (\Exception $e) {
            $this->logger->error('Redis info failed', [
                'error' => $e->getMessage()
            ]);
            
            return [];
        }
    }

    /**
     * Increment a numeric value
     *
     * @param string $key
     * @param int $value
     * @return int|false
     */
    public function increment(string $key, int $value = 1): int|false
    {
        try {
            return $this->client->incrby($key, $value);
        } catch (\Exception $e) {
            $this->logger->error('Redis increment failed', [
                'key' => $key,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Decrement a numeric value
     *
     * @param string $key
     * @param int $value
     * @return int|false
     */
    public function decrement(string $key, int $value = 1): int|false
    {
        try {
            return $this->client->decrby($key, $value);
        } catch (\Exception $e) {
            $this->logger->error('Redis decrement failed', [
                'key' => $key,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }
}