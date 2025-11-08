<?php

namespace App\Dashboard;

class AnalyticsService
{
    private $dataProvider;
    
    public function __construct()
    {
        $this->dataProvider = new DataProvider();
    }
    
    /**
     * Analyze system performance and generate insights
     *
     * @return array
     */
    public function analyzePerformance(): array
    {
        $dashboardData = $this->dataProvider->getDashboardData();
        $systemMetrics = $dashboardData['system_metrics'];
        
        $insights = [];
        
        // CPU usage analysis
        if ($systemMetrics['cpu_usage'] > 80) {
            $insights[] = [
                'type' => 'warning',
                'message' => 'High CPU usage detected',
                'severity' => 'high',
                'recommendation' => 'Consider scaling up resources or optimizing processes'
            ];
        } elseif ($systemMetrics['cpu_usage'] > 60) {
            $insights[] = [
                'type' => 'info',
                'message' => 'Moderate CPU usage',
                'severity' => 'medium',
                'recommendation' => 'Monitor CPU usage trends'
            ];
        }
        
        // Memory usage analysis
        if ($systemMetrics['memory_usage'] > 85) {
            $insights[] = [
                'type' => 'warning',
                'message' => 'High memory usage detected',
                'severity' => 'high',
                'recommendation' => 'Check for memory leaks or consider increasing memory allocation'
            ];
        }
        
        // Connection analysis
        if ($systemMetrics['active_connections'] > 40) {
            $insights[] = [
                'type' => 'info',
                'message' => 'High number of active connections',
                'severity' => 'medium',
                'recommendation' => 'Monitor connection usage and consider connection pooling'
            ];
        }
        
        // Network traffic analysis
        if ($systemMetrics['network_traffic'] > 8000) {
            $insights[] = [
                'type' => 'info',
                'message' => 'High network traffic',
                'severity' => 'medium',
                'recommendation' => 'Monitor bandwidth usage'
            ];
        }
        
        return [
            'timestamp' => date('c'),
            'insights' => $insights,
            'overall_health' => $this->calculateOverallHealth($insights)
        ];
    }
    
    /**
     * Calculate overall system health based on insights
     *
     * @param array $insights
     * @return string
     */
    private function calculateOverallHealth(array $insights): string
    {
        $hasHighSeverity = false;
        $hasMediumSeverity = false;
        
        foreach ($insights as $insight) {
            if ($insight['severity'] === 'high') {
                $hasHighSeverity = true;
            } elseif ($insight['severity'] === 'medium') {
                $hasMediumSeverity = true;
            }
        }
        
        if ($hasHighSeverity) {
            return 'critical';
        } elseif ($hasMediumSeverity) {
            return 'warning';
        }
        
        return 'healthy';
    }
    
    /**
     * Generate performance report
     *
     * @param int $period Hours to analyze
     * @return array
     */
    public function generatePerformanceReport(int $period = 24): array
    {
        $report = [
            'period' => $period,
            'generated_at' => date('c'),
            'metrics_summary' => [],
            'trends' => [],
            'recommendations' => []
        ];
        
        // Generate mock summary data
        $report['metrics_summary'] = [
            'avg_cpu_usage' => rand(30, 70) + (rand(0, 99) / 100),
            'avg_memory_usage' => rand(40, 80) + (rand(0, 99) / 100),
            'peak_connections' => rand(20, 50),
            'total_requests' => rand(1000, 10000)
        ];
        
        // Generate mock trends
        $report['trends'] = [
            'cpu_trend' => rand(-10, 10) > 0 ? 'increasing' : 'decreasing',
            'memory_trend' => rand(-10, 10) > 0 ? 'increasing' : 'decreasing',
            'traffic_trend' => rand(-10, 10) > 0 ? 'increasing' : 'decreasing'
        ];
        
        // Generate mock recommendations
        $report['recommendations'] = [
            'Consider implementing caching for frequently accessed data',
            'Review and optimize database queries',
            'Monitor resource usage during peak hours'
        ];
        
        return $report;
    }
}