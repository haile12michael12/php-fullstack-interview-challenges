<?php

namespace App\Dashboard;

class MetricsCollector
{
    private $startTime;
    
    public function __construct()
    {
        $this->startTime = microtime(true);
    }
    
    /**
     * Collect current system metrics
     *
     * @return array
     */
    public function collect(): array
    {
        return [
            'timestamp' => date('c'),
            'uptime' => $this->getUptime(),
            'memory_usage' => $this->getMemoryUsage(),
            'cpu_usage' => $this->getCpuUsage(),
            'active_connections' => $this->getActiveConnections(),
            'network_traffic' => $this->getNetworkTraffic(),
            'requests_per_second' => $this->getRequestsPerSecond(),
            'system_load' => $this->getSystemLoad()
        ];
    }
    
    /**
     * Get application uptime in seconds
     *
     * @return int
     */
    private function getUptime(): int
    {
        return (int)(microtime(true) - $this->startTime);
    }
    
    /**
     * Get memory usage percentage
     *
     * @return float
     */
    private function getMemoryUsage(): float
    {
        if (function_exists('memory_get_usage')) {
            $used = memory_get_usage();
            $total = memory_get_peak_usage();
            return $total > 0 ? round(($used / $total) * 100, 2) : 0;
        }
        
        return 0;
    }
    
    /**
     * Get simulated CPU usage percentage
     *
     * @return float
     */
    private function getCpuUsage(): float
    {
        // Simulate CPU usage with random values for demonstration
        // In a real implementation, you would read from system stats
        return round(rand(10, 90) + (rand(0, 99) / 100), 2);
    }
    
    /**
     * Get active connections count
     *
     * @return int
     */
    private function getActiveConnections(): int
    {
        // This would be populated by the WebSocket server
        // For now, we'll simulate with a random number
        return rand(1, 50);
    }
    
    /**
     * Get network traffic (simulated)
     *
     * @return int
     */
    private function getNetworkTraffic(): int
    {
        // Simulate network traffic in bytes per second
        return rand(1000, 10000);
    }
    
    /**
     * Get requests per second (simulated)
     *
     * @return float
     */
    private function getRequestsPerSecond(): float
    {
        // Simulate requests per second
        return round(rand(1, 100) + (rand(0, 99) / 100), 2);
    }
    
    /**
     * Get system load (simulated)
     *
     * @return array
     */
    private function getSystemLoad(): array
    {
        return [
            '1min' => round(rand(0, 5) + (rand(0, 99) / 100), 2),
            '5min' => round(rand(0, 5) + (rand(0, 99) / 100), 2),
            '15min' => round(rand(0, 5) + (rand(0, 99) / 100), 2)
        ];
    }
    
    /**
     * Get database metrics
     *
     * @return array
     */
    public function getDatabaseMetrics(): array
    {
        return [
            'connections' => rand(1, 20),
            'queries_per_second' => round(rand(1, 50) + (rand(0, 99) / 100), 2),
            'slow_queries' => rand(0, 5)
        ];
    }
    
    /**
     * Get cache metrics
     *
     * @return array
     */
    public function getCacheMetrics(): array
    {
        return [
            'hit_rate' => round(rand(80, 99) + (rand(0, 99) / 100), 2),
            'miss_rate' => round(rand(1, 20) + (rand(0, 99) / 100), 2),
            'entries' => rand(100, 10000)
        ];
    }
}