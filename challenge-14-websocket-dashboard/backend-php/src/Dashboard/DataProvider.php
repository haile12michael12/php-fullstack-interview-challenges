<?php

namespace App\Dashboard;

class DataProvider
{
    private $metricsCollector;
    
    public function __construct()
    {
        $this->metricsCollector = new MetricsCollector();
    }
    
    /**
     * Get dashboard data
     *
     * @return array
     */
    public function getDashboardData(): array
    {
        return [
            'system_metrics' => $this->metricsCollector->collect(),
            'database_metrics' => $this->metricsCollector->getDatabaseMetrics(),
            'cache_metrics' => $this->metricsCollector->getCacheMetrics(),
            'timestamp' => date('c')
        ];
    }
    
    /**
     * Get historical metrics for charts
     *
     * @param string $metricType
     * @param int $hours
     * @return array
     */
    public function getHistoricalMetrics(string $metricType, int $hours = 24): array
    {
        $data = [];
        $now = time();
        
        // Generate mock historical data
        for ($i = $hours; $i >= 0; $i--) {
            $timestamp = $now - ($i * 3600);
            
            switch ($metricType) {
                case 'cpu':
                    $value = rand(10, 90) + (rand(0, 99) / 100);
                    break;
                case 'memory':
                    $value = rand(20, 80) + (rand(0, 99) / 100);
                    break;
                case 'network':
                    $value = rand(1000, 10000);
                    break;
                default:
                    $value = rand(1, 100);
            }
            
            $data[] = [
                'timestamp' => date('c', $timestamp),
                'value' => $value
            ];
        }
        
        return $data;
    }
    
    /**
     * Get system status
     *
     * @return array
     */
    public function getSystemStatus(): array
    {
        $metrics = $this->metricsCollector->collect();
        
        // Determine system health based on metrics
        $health = 'healthy';
        $issues = [];
        
        if ($metrics['cpu_usage'] > 80) {
            $health = 'warning';
            $issues[] = 'High CPU usage';
        }
        
        if ($metrics['memory_usage'] > 85) {
            $health = 'warning';
            $issues[] = 'High memory usage';
        }
        
        if ($metrics['system_load']['1min'] > 4) {
            $health = 'warning';
            $issues[] = 'High system load';
        }
        
        return [
            'health' => $health,
            'issues' => $issues,
            'timestamp' => date('c')
        ];
    }
}