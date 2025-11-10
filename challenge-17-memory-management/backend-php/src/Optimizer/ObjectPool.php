<?php

namespace App\Optimizer;

/**
 * Object Pool - Implements object pooling pattern to reduce memory allocation
 */
class ObjectPool
{
    private array $pool = [];
    private string $className;
    private array $initialParams;
    
    public function __construct(string $className, array $initialParams = [])
    {
        $this->className = $className;
        $this->initialParams = $initialParams;
        
        if (!class_exists($className)) {
            throw new \InvalidArgumentException("Class {$className} does not exist");
        }
    }
    
    /**
     * Acquire an object from the pool
     */
    public function acquire(): object
    {
        if (!empty($this->pool)) {
            return array_pop($this->pool);
        }
        
        // Create new instance
        $reflection = new \ReflectionClass($this->className);
        if (!empty($this->initialParams)) {
            return $reflection->newInstanceArgs($this->initialParams);
        }
        
        return $reflection->newInstance();
    }
    
    /**
     * Release an object back to the pool
     */
    public function release(object $object): void
    {
        // Reset object state if possible
        if (method_exists($object, 'reset')) {
            $object->reset();
        }
        
        // Add to pool
        $this->pool[] = $object;
    }
    
    /**
     * Get pool size
     */
    public function getPoolSize(): int
    {
        return count($this->pool);
    }
    
    /**
     * Pre-populate the pool with objects
     */
    public function prepopulate(int $count): void
    {
        for ($i = 0; $i < $count; $i++) {
            $reflection = new \ReflectionClass($this->className);
            if (!empty($this->initialParams)) {
                $object = $reflection->newInstanceArgs($this->initialParams);
            } else {
                $object = $reflection->newInstance();
            }
            $this->pool[] = $object;
        }
    }
    
    /**
     * Clear the pool
     */
    public function clear(): void
    {
        $this->pool = [];
    }
}