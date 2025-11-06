<?php

namespace App\Middleware;

class PerformanceMonitorMiddleware
{
    private float $startTime;
    private array $metrics;

    public function __construct()
    {
        $this->startTime = microtime(true);
        $this->metrics = [];
    }

    public function handle(): void
    {
        // Record start time
        $this->startTime = microtime(true);
    }

    public function after(): void
    {
        $endTime = microtime(true);
        $executionTime = ($endTime - $this->startTime) * 1000; // Convert to milliseconds
        
        $this->metrics = [
            'execution_time_ms' => $executionTime,
            'memory_usage' => memory_get_peak_usage(true),
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        // Log performance metrics
        error_log("Performance: " . json_encode($this->metrics));
    }

    public function getMetrics(): array
    {
        return $this->metrics;
    }
}