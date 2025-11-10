<?php

namespace App\Optimizer;

/**
 * Lazy Loader - Implements lazy loading pattern to defer object initialization
 */
class LazyLoader
{
    private array $loadedObjects = [];
    private array $factories = [];
    
    /**
     * Register a factory for lazy loading
     */
    public function register(string $name, callable $factory): void
    {
        $this->factories[$name] = $factory;
    }
    
    /**
     * Get an object, loading it if necessary
     */
    public function get(string $name)
    {
        if (!isset($this->loadedObjects[$name])) {
            if (!isset($this->factories[$name])) {
                throw new \InvalidArgumentException("No factory registered for {$name}");
            }
            
            $this->loadedObjects[$name] = call_user_func($this->factories[$name]);
        }
        
        return $this->loadedObjects[$name];
    }
    
    /**
     * Check if an object has been loaded
     */
    public function isLoaded(string $name): bool
    {
        return isset($this->loadedObjects[$name]);
    }
    
    /**
     * Unload an object to free memory
     */
    public function unload(string $name): void
    {
        if (isset($this->loadedObjects[$name])) {
            // If it's an object, set to null to allow garbage collection
            if (is_object($this->loadedObjects[$name])) {
                $this->loadedObjects[$name] = null;
            }
            unset($this->loadedObjects[$name]);
        }
    }
    
    /**
     * Get list of loaded objects
     */
    public function getLoadedObjects(): array
    {
        return array_keys($this->loadedObjects);
    }
    
    /**
     * Clear all loaded objects
     */
    public function clear(): void
    {
        foreach ($this->loadedObjects as $name => $object) {
            if (is_object($object)) {
                $this->loadedObjects[$name] = null;
            }
        }
        $this->loadedObjects = [];
    }
}