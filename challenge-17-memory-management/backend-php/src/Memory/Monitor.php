<?php

namespace App\Memory;

/**
 * Memory Monitor - Continuously monitors memory usage and triggers alerts
 */
class Monitor
{
    private Profiler $profiler;
    private int $alertThreshold;
    private array $alerts = [];
    
    public function __construct(Profiler $profiler, int $alertThreshold = 52428800) // 50MB default
    {
        $this->profiler = $profiler;
        $this->alertThreshold = $alertThreshold;
    }
    
    /**
     * Check current memory usage and trigger alerts if needed
     */
    public function checkMemoryUsage(string $context = ''): bool
    {
        $currentUsage = memory_get_usage(true);
        
        if ($currentUsage > $this->alertThreshold) {
            $alert = [
                'timestamp' => time(),
                'context' => $context,
                'memory_usage' => $currentUsage,
                'threshold' => $this->alertThreshold,
                'formatted_usage' => $this->formatBytes($currentUsage),
                'formatted_threshold' => $this->formatBytes($this->alertThreshold),
                'exceeded_by' => $currentUsage - $this->alertThreshold,
            ];
            
            $this->alerts[] = $alert;
            return true;
        }
        
        return false;
    }
    
    /**
     * Get all alerts
     */
    public function getAlerts(): array
    {
        return $this->alerts;
    }
    
    /**
     * Get current memory status
     */
    public function getStatus(): array
    {
        return [
            'current_usage' => memory_get_usage(true),
            'peak_usage' => memory_get_peak_usage(true),
            'formatted_usage' => $this->formatBytes(memory_get_usage(true)),
            'formatted_peak' => $this->formatBytes(memory_get_peak_usage(true)),
            'threshold' => $this->alertThreshold,
            'formatted_threshold' => $this->formatBytes($this->alertThreshold),
            'alert_triggered' => memory_get_usage(true) > $this->alertThreshold,
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
     * Reset alerts
     */
    public function resetAlerts(): void
    {
        $this->alerts = [];
    }
}