<?php

namespace App\Utils;

/**
 * Weak Reference Manager - Manages weak references to prevent memory leaks
 */
class WeakReferenceManager
{
    private array $references = [];
    
    /**
     * Create a weak reference to an object
     */
    public function createReference(object $object, string $key = null): \WeakReference
    {
        $reference = \WeakReference::create($object);
        
        if ($key === null) {
            $this->references[] = $reference;
        } else {
            $this->references[$key] = $reference;
        }
        
        return $reference;
    }
    
    /**
     * Get object from weak reference
     */
    public function getObject(string $key)
    {
        if (!isset($this->references[$key])) {
            return null;
        }
        
        return $this->references[$key]->get();
    }
    
    /**
     * Check if referenced object still exists
     */
    public function isValid(string $key): bool
    {
        if (!isset($this->references[$key])) {
            return false;
        }
        
        return $this->references[$key]->get() !== null;
    }
    
    /**
     * Get all valid references
     */
    public function getValidReferences(): array
    {
        $valid = [];
        foreach ($this->references as $key => $reference) {
            if ($reference->get() !== null) {
                $valid[$key] = $reference;
            }
        }
        return $valid;
    }
    
    /**
     * Clean up invalid references
     */
    public function cleanup(): int
    {
        $count = 0;
        foreach ($this->references as $key => $reference) {
            if ($reference->get() === null) {
                unset($this->references[$key]);
                $count++;
            }
        }
        return $count;
    }
    
    /**
     * Get count of all references
     */
    public function getCount(): int
    {
        return count($this->references);
    }
    
    /**
     * Get count of valid references
     */
    public function getValidCount(): int
    {
        return count($this->getValidReferences());
    }
}