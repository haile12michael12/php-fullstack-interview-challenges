<?php

namespace App\Leak;

/**
 * Memory Leak Cleaner - Cleans up memory leaks and optimizes memory usage
 */
class Cleaner
{
    /**
     * Clean up memory by forcing garbage collection
     */
    public function forceCleanup(): array
    {
        $before = memory_get_usage(true);
        
        // Force garbage collection
        $cycles = gc_collect_cycles();
        gc_mem_caches();
        
        $after = memory_get_usage(true);
        
        return [
            'memory_reclaimed' => $before - $after,
            'cycles_collected' => $cycles,
            'formatted_reclaimed' => $this->formatBytes($before - $after),
        ];
    }
    
    /**
     * Clean up specific objects
     */
    public function cleanupObjects(array &$objects): void
    {
        foreach ($objects as $key => $object) {
            // If it's a weak reference, check if object still exists
            if ($object instanceof \WeakReference) {
                if ($object->get() === null) {
                    unset($objects[$key]);
                }
            } else {
                // Set to null to allow garbage collection
                $objects[$key] = null;
                unset($objects[$key]);
            }
        }
        
        // Force garbage collection
        gc_collect_cycles();
    }
    
    /**
     * Clean up resources
     */
    public function cleanupResources(array &$resources): void
    {
        foreach ($resources as $key => $resource) {
            if (is_resource($resource)) {
                fclose($resource);
            }
            unset($resources[$key]);
        }
    }
    
    /**
     * Reset static properties that might hold references
     */
    public function resetStaticProperties(string $className): void
    {
        $reflection = new \ReflectionClass($className);
        foreach ($reflection->getProperties(\ReflectionProperty::IS_STATIC) as $property) {
            $property->setAccessible(true);
            $value = $property->getValue();
            
            // Only reset if it's an array or object
            if (is_array($value) || is_object($value)) {
                $property->setValue(null);
            }
        }
    }
    
    /**
     * Clear all cached data
     */
    public function clearCache(): void
    {
        if (function_exists('apcu_clear_cache')) {
            apcu_clear_cache();
        }
        
        if (function_exists('opcache_reset')) {
            opcache_reset();
        }
    }
    
    /**
     * Format bytes to human readable format
     */
    private function formatBytes(int $size, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
            $size /= 1024;
        }
        
        return round($size, $precision) . ' ' . $units[$i];
    }
}