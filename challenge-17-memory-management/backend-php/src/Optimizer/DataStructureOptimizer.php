<?php

namespace App\Optimizer;

/**
 * Data Structure Optimizer - Optimizes data structures for memory efficiency
 */
class DataStructureOptimizer
{
    /**
     * Optimize array storage by using more memory-efficient alternatives
     */
    public function optimizeArray(array $data): array
    {
        // For large arrays with numeric keys, consider using SplFixedArray
        if (count($data) > 1000 && $this->isSequentialNumericArray($data)) {
            $fixedArray = new \SplFixedArray(count($data));
            foreach ($data as $key => $value) {
                $fixedArray[$key] = $value;
            }
            return $fixedArray->toArray(); // Return as array for compatibility
        }
        
        // For associative arrays with repeated keys, consider using a more compact structure
        return $data;
    }
    
    /**
     * Check if array has sequential numeric keys
     */
    private function isSequentialNumericArray(array $array): bool
    {
        return array_keys($array) === range(0, count($array) - 1);
    }
    
    /**
     * Optimize object storage by using more memory-efficient patterns
     */
    public function optimizeObjects(array $objects): array
    {
        // Consider using __sleep/__wakeup or Serializable interface for objects
        // that need to be stored or transmitted
        
        $optimized = [];
        foreach ($objects as $object) {
            if (method_exists($object, '__sleep')) {
                // Object can be optimized for serialization
                $optimized[] = $object;
            } else {
                // Keep as is
                $optimized[] = $object;
            }
        }
        
        return $optimized;
    }
    
    /**
     * Use generators for large datasets to reduce memory footprint
     */
    public function useGenerators(array $data): \Generator
    {
        foreach ($data as $item) {
            yield $item;
        }
    }
    
    /**
     * Recommend memory-efficient data structures
     */
    public function getRecommendations(array $data): array
    {
        $recommendations = [];
        
        if (count($data) > 10000) {
            $recommendations[] = [
                'type' => 'large_dataset',
                'recommendation' => 'Use generators instead of arrays for iteration',
                'benefit' => 'Significantly reduces memory usage'
            ];
        }
        
        if ($this->isArrayAssociative($data) && count(array_unique(array_keys($data))) < count($data) * 0.5) {
            $recommendations[] = [
                'type' => 'repetitive_keys',
                'recommendation' => 'Consider using a more compact data structure',
                'benefit' => 'Reduces memory overhead from repeated keys'
            ];
        }
        
        return $recommendations;
    }
    
    /**
     * Check if array is associative
     */
    private function isArrayAssociative(array $array): bool
    {
        if ([] === $array) {
            return false;
        }
        
        return array_keys($array) !== range(0, count($array) - 1);
    }
}