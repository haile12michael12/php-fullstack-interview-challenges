<?php

declare(strict_types=1);

namespace SharedBackend\Cache;

use SharedBackend\Core\Config;
use SharedBackend\Core\Logger;

class MemcachedCache implements CacheInterface
{
    private \Memcached $client;
    private Logger $logger;

    public function __construct(Config $config, Logger $logger)
    {
        $this->logger = $logger;
        
        $this->client = new \Memcached();
        
        $servers = $config->get('memcached.servers', [
            ['host' => '127.0.0.1', 'port' => 11211, 'weight' => 0]
        ]);
        
        foreach ($servers as $server) {
            $this->client->addServer(
                $server['host'],
                $server['port'],
                $server['weight'] ?? 0
            );
        }
        
        // Set options
        $options = $config->get('memcached.options', []);
        foreach ($options as $option => $value) {
            $this->client->setOption($option, $value);
        }
        
        $this->logger->info('Memcached initialized', ['servers' => $servers]);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $value = $this->client->get($key);
        
        if ($this->client->getResultCode() === \Memcached::RES_NOTFOUND) {
            return $default;
        }
        
        return $value;
    }

    public function set(string $key, mixed $value, ?int $ttl = null): bool
    {
        return $this->client->set($key, $value, $ttl ?? 0);
    }

    public function delete(string $key): bool
    {
        return $this->client->delete($key);
    }

    public function has(string $key): bool
    {
        $this->client->get($key);
        return $this->client->getResultCode() === \Memcached::RES_SUCCESS;
    }

    public function clear(): bool
    {
        return $this->client->flush();
    }

    public function getMultiple(array $keys, mixed $default = null): array
    {
        $values = $this->client->getMulti($keys);
        
        if ($values === false) {
            return array_fill_keys($keys, $default);
        }
        
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = $values[$key] ?? $default;
        }
        
        return $result;
    }

    public function setMultiple(array $values, ?int $ttl = null): bool
    {
        return $this->client->setMulti($values, $ttl ?? 0);
    }

    public function deleteMultiple(array $keys): bool
    {
        return $this->client->deleteMulti($keys);
    }

    /**
     * Get cache statistics
     *
     * @return array
     */
    public function getStats(): array
    {
        return $this->client->getStats();
    }

    /**
     * Increment a numeric value
     *
     * @param string $key
     * @param int $offset
     * @param int $initial
     * @param int $expiry
     * @return int|false
     */
    public function increment(string $key, int $offset = 1, int $initial = 0, int $expiry = 0): int|false
    {
        return $this->client->increment($key, $offset, $initial, $expiry);
    }

    /**
     * Decrement a numeric value
     *
     * @param string $key
     * @param int $offset
     * @param int $initial
     * @param int $expiry
     * @return int|false
     */
    public function decrement(string $key, int $offset = 1, int $initial = 0, int $expiry = 0): int|false
    {
        return $this->client->decrement($key, $offset, $initial, $expiry);
    }
}