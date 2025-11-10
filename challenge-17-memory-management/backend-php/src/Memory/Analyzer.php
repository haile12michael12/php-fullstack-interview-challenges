<?php

namespace App\Memory;

/**
 * Memory Analyzer - Analyzes memory patterns and provides optimization suggestions
 */
class Analyzer
{
    private Profiler $profiler;
    
    public function __construct(Profiler $profiler)
    {
        $this->profiler = $profiler;
    }
    
    /**
     * Analyze memory growth patterns
     */
    public function analyzeGrowth(): array
    {
        $snapshots = $this->profiler->getSnapshots();
        
        if (count($snapshots) < 2) {
            return ['error' => 'Not enough data for analysis'];
        }
        
        $growthRates = [];
        $previousUsage = $snapshots[0]['memory_usage'];
        
        for ($i = 1; $i < count($snapshots); $i++) {
            $currentUsage = $snapshots[$i]['memory_usage'];
            $growthRate = ($currentUsage - $previousUsage) / $previousUsage * 100;
            $growthRates[] = $growthRate;
            $previousUsage = $currentUsage;
        }
        
        return [
            'average_growth_rate' => array_sum($growthRates) / count($growthRates),
            'max_growth_rate' => max($growthRates),
            'min_growth_rate' => min($growthRates),
            'total_growth' => end($snapshots)['memory_usage'] - $snapshots[0]['memory_usage'],
            'formatted_total_growth' => $this->formatBytes(end($snapshots)['memory_usage'] - $snapshots[0]['memory_usage']),
        ];
    }
    
    /**
     * Identify potential memory issues
     */
    public function identifyIssues(): array
    {
        $issues = [];
        $snapshots = $this->profiler->getSnapshots();
        
        if (count($snapshots) < 2) {
            return $issues;
        }
        
        // Check for consistent memory growth
        $usages = array_column($snapshots, 'memory_usage');
        $trend = $this->calculateTrend($usages);
        
        if ($trend > 0.1) { // Positive trend > 10%
            $issues[] = [
                'type' => 'memory_leak',
                'severity' => 'high',
                'description' => 'Consistent memory growth detected, possible memory leak',
                'recommendation' => 'Check for unreleased resources and circular references'
            ];
        }
        
        // Check for high peak usage
        $peakUsages = array_column($snapshots, 'peak_usage');
        $avgPeak = array_sum($peakUsages) / count($peakUsages);
        $maxPeak = max($peakUsages);
        
        if ($maxPeak > $avgPeak * 2) {
            $issues[] = [
                'type' => 'spike_usage',
                'severity' => 'medium',
                'description' => 'High memory spikes detected',
                'recommendation' => 'Investigate operations causing memory spikes'
            ];
        }
        
        return $issues;
    }
    
    /**
     * Provide optimization suggestions
     */
    public function getSuggestions(): array
    {
        $suggestions = [];
        
        $suggestions[] = [
            'type' => 'general',
            'priority' => 'high',
            'description' => 'Implement object pooling for frequently created objects',
            'benefit' => 'Reduces memory allocation overhead'
        ];
        
        $suggestions[] = [
            'type' => 'general',
            'priority' => 'high',
            'description' => 'Use generators for large datasets',
            'benefit' => 'Reduces memory footprint when processing large data'
        ];
        
        $suggestions[] = [
            'type' => 'general',
            'priority' => 'medium',
            'description' => 'Implement lazy loading for heavy objects',
            'benefit' => 'Delays memory allocation until actually needed'
        ];
        
        $suggestions[] = [
            'type' => 'general',
            'priority' => 'medium',
            'description' => 'Use weak references to prevent circular references',
            'benefit' => 'Allows garbage collector to free memory more effectively'
        ];
        
        return $suggestions;
    }
    
    /**
     * Calculate linear trend of data
     */
    private function calculateTrend(array $data): float
    {
        $n = count($data);
        if ($n < 2) {
            return 0;
        }
        
        // Simple linear regression slope
        $sumX = array_sum(range(1, $n));
        $sumY = array_sum($data);
        $sumXY = 0;
        $sumXX = 0;
        
        for ($i = 0; $i < $n; $i++) {
            $x = $i + 1;
            $y = $data[$i];
            $sumXY += $x * $y;
            $sumXX += $x * $x;
        }
        
        $slope = ($n * $sumXY - $sumX * $sumY) / ($n * $sumXX - $sumX * $sumX);
        return $slope;
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
}