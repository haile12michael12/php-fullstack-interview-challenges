<?php

namespace App\Cache;

interface CacheInterface
{
    /**
     * Fetches an entry from the cache.
     *
     * @param string $key The cache key
     * @return mixed The cached data or null if not found
     */
    public function get(string $key);

    /**
     * Stores data in the cache.
     *
     * @param string $key The cache key
     * @param mixed $value The data to cache
     * @param int $ttl Time to live in seconds
     * @return bool True if the entry was successfully stored, false otherwise
     */
    public function set(string $key, $value, int $ttl = 0): bool;

    /**
     * Deletes an entry from the cache.
     *
     * @param string $key The cache key
     * @return bool True if the entry was successfully deleted, false otherwise
     */
    public function delete(string $key): bool;

    /**
     * Checks if an entry exists in the cache.
     *
     * @param string $key The cache key
     * @return bool True if the entry exists, false otherwise
     */
    public function has(string $key): bool;

    /**
     * Clears all entries from the cache.
     *
     * @return bool True if the cache was successfully cleared, false otherwise
     */
    public function clear(): bool;

    /**
     * Gets multiple entries from the cache.
     *
     * @param array $keys The cache keys
     * @return array An array of cached data indexed by key
     */
    public function getMultiple(array $keys): array;

    /**
     * Stores multiple entries in the cache.
     *
     * @param array $values An array of key-value pairs to cache
     * @param int $ttl Time to live in seconds
     * @return bool True if the entries were successfully stored, false otherwise
     */
    public function setMultiple(array $values, int $ttl = 0): bool;

    /**
     * Deletes multiple entries from the cache.
     *
     * @param array $keys The cache keys to delete
     * @return bool True if the entries were successfully deleted, false otherwise
     */
    public function deleteMultiple(array $keys): bool;
}