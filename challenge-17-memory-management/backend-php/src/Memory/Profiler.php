<?php

namespace App\Memory;

/**
 * Memory Profiler - Tracks and profiles memory usage in PHP applications
 */
class Profiler
{
    private array $snapshots = [];
    private bool $enabled;
    
    public function __construct(bool $enabled = true)
    {
        $this->enabled = $enabled;
    }
    
    /**
     * Take a snapshot of current memory usage
     */
    public function snapshot(string $label = ''): array
    {
        if (!$this->enabled) {
            return [];
        }
        
        $snapshot = [
            'label' => $label,
            'time' => microtime(true),
            'memory_usage' => memory_get_usage(true),
            'memory_real_usage' => memory_get_usage(false),
            'peak_usage' => memory_get_peak_usage(true),
            'formatted_usage' => $this->formatBytes(memory_get_usage(true)),
            'formatted_peak' => $this->formatBytes(memory_get_peak_usage(true)),
        ];
        
        $this->snapshots[] = $snapshot;
        return $snapshot;
    }
    
    /**
     * Get all snapshots
     */
    public function getSnapshots(): array
    {
        return $this->snapshots;
    }
    
    /**
     * Calculate difference between two snapshots
     */
    public function diff(int $start, int $end): array
    {
        if (!isset($this->snapshots[$start]) || !isset($this->snapshots[$end])) {
            return [];
        }
        
        $startSnapshot = $this->snapshots[$start];
        $endSnapshot = $this->snapshots[$end];
        
        return [
            'label' => "Diff: {$startSnapshot['label']} -> {$endSnapshot['label']}",
            'memory_diff' => $endSnapshot['memory_usage'] - $startSnapshot['memory_usage'],
            'peak_diff' => $endSnapshot['peak_usage'] - $startSnapshot['peak_usage'],
            'formatted_diff' => $this->formatBytes($endSnapshot['memory_usage'] - $startSnapshot['memory_usage']),
            'execution_time' => $endSnapshot['time'] - $startSnapshot['time'],
        ];
    }
    
    /**
     * Get current memory usage statistics
     */
    public function getStats(): array
    {
        if (empty($this->snapshots)) {
            return [];
        }
        
        $usages = array_column($this->snapshots, 'memory_usage');
        $peaks = array_column($this->snapshots, 'peak_usage');
        
        return [
            'total_snapshots' => count($this->snapshots),
            'average_memory' => array_sum($usages) / count($usages),
            'max_memory' => max($usages),
            'min_memory' => min($usages),
            'average_peak' => array_sum($peaks) / count($peaks),
            'max_peak' => max($peaks),
            'formatted_average' => $this->formatBytes(array_sum($usages) / count($usages)),
            'formatted_max' => $this->formatBytes(max($usages)),
        ];
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
    
    /**
     * Reset profiler
     */
    public function reset(): void
    {
        $this->snapshots = [];
    }
}