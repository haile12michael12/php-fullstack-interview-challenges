<?php

declare(strict_types=1);

namespace SharedBackend\Cache;

interface CacheInterface
{
    /**
     * Retrieve an item from the cache by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed;

    /**
     * Store an item in the cache.
     *
     * @param string $key
     * @param mixed $value
     * @param int|null $ttl Time to live in seconds
     * @return bool
     */
    public function set(string $key, mixed $value, ?int $ttl = null): bool;

    /**
     * Delete an item from the cache.
     *
     * @param string $key
     * @return bool
     */
    public function delete(string $key): bool;

    /**
     * Check if an item exists in the cache.
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * Clear the entire cache.
     *
     * @return bool
     */
    public function clear(): bool;

    /**
     * Get multiple items from the cache.
     *
     * @param array $keys
     * @param mixed $default
     * @return array
     */
    public function getMultiple(array $keys, mixed $default = null): array;

    /**
     * Set multiple items in the cache.
     *
     * @param array $values
     * @param int|null $ttl
     * @return bool
     */
    public function setMultiple(array $values, ?int $ttl = null): bool;

    /**
     * Delete multiple items from the cache.
     *
     * @param array $keys
     * @return bool
     */
    public function deleteMultiple(array $keys): bool;
}