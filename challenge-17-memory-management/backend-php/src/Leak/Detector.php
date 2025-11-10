<?php

namespace App\Leak;

/**
 * Memory Leak Detector - Identifies potential memory leaks in PHP applications
 */
class Detector
{
    private array $trackedObjects = [];
    private array $leaks = [];
    
    /**
     * Track an object for potential leak detection
     */
    public function track(object $object, string $label = ''): void
    {
        $hash = spl_object_hash($object);
        $this->trackedObjects[$hash] = [
            'object' => $object,
            'label' => $label,
            'created' => microtime(true),
            'refcount' => gc_status()['runs'] ?? 0
        ];
    }
    
    /**
     * Detect potential memory leaks
     */
    public function detectLeaks(): array
    {
        // Force garbage collection
        gc_collect_cycles();
        
        $status = gc_status();
        $this->leaks = [];
        
        // Check for circular references
        if ($status['cycles_collected'] < $status['cycles_found']) {
            $this->leaks[] = [
                'type' => 'circular_reference',
                'description' => 'Potential circular references detected',
                'collected' => $status['cycles_collected'],
                'found' => $status['cycles_found'],
                'uncollected' => $status['cycles_found'] - $status['cycles_collected']
            ];
        }
        
        // Check tracked objects
        foreach ($this->trackedObjects as $hash => $info) {
            // Check if object still exists
            if (!array_key_exists($hash, $this->trackedObjects)) {
                continue;
            }
            
            // Check if object has been alive for too long
            $age = microtime(true) - $info['created'];
            if ($age > 300) { // 5 minutes
                $this->leaks[] = [
                    'type' => 'long_lived_object',
                    'description' => 'Object has been alive for an extended period',
                    'label' => $info['label'],
                    'age_seconds' => $age
                ];
            }
        }
        
        return $this->leaks;
    }
    
    /**
     * Get garbage collection statistics
     */
    public function getGCStats(): array
    {
        return gc_status();
    }
    
    /**
     * Enable garbage collection monitoring
     */
    public function enableMonitoring(): void
    {
        gc_enable();
        gc_collect_cycles(); // Reset counters
    }
    
    /**
     * Get potential leaks
     */
    public function getLeaks(): array
    {
        return $this->leaks;
    }
    
    /**
     * Clear tracked objects
     */
    public function clearTracked(): void
    {
        $this->trackedObjects = [];
    }
}