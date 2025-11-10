<?php

namespace App\Monitoring;

/**
 * Report Generator - Generates memory usage reports
 */
class ReportGenerator
{
    /**
     * Generate a comprehensive memory usage report
     */
    public function generateReport(array $data = []): array
    {
        $report = [
            'generated_at' => date('Y-m-d H:i:s'),
            'memory_stats' => $this->getMemoryStats(),
            'gc_stats' => $this->getGCStats(),
            'system_info' => $this->getSystemInfo(),
        ];
        
        // Merge additional data
        return array_merge($report, $data);
    }
    
    /**
     * Get current memory statistics
     */
    private function getMemoryStats(): array
    {
        return [
            'current_usage' => memory_get_usage(true),
            'real_usage' => memory_get_usage(false),
            'peak_usage' => memory_get_peak_usage(true),
            'formatted_current' => $this->formatBytes(memory_get_usage(true)),
            'formatted_real' => $this->formatBytes(memory_get_usage(false)),
            'formatted_peak' => $this->formatBytes(memory_get_peak_usage(true)),
        ];
    }
    
    /**
     * Get garbage collection statistics
     */
    private function getGCStats(): array
    {
        if (function_exists('gc_status')) {
            return gc_status();
        }
        
        return [
            'not_available' => 'GC stats not available'
        ];
    }
    
    /**
     * Get system information
     */
    private function getSystemInfo(): array
    {
        return [
            'php_version' => PHP_VERSION,
            'sapi' => PHP_SAPI,
            'os' => PHP_OS,
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
        ];
    }
    
    /**
     * Generate trend report from historical data
     */
    public function generateTrendReport(array $history): array
    {
        if (empty($history)) {
            return ['error' => 'No historical data provided'];
        }
        
        $usages = array_column($history, 'memory_usage');
        $peaks = array_column($history, 'peak_usage');
        
        return [
            'period_start' => $history[0]['timestamp'] ?? null,
            'period_end' => end($history)['timestamp'] ?? null,
            'average_usage' => array_sum($usages) / count($usages),
            'average_peak' => array_sum($peaks) / count($peaks),
            'max_usage' => max($usages),
            'min_usage' => min($usages),
            'formatted_average' => $this->formatBytes(array_sum($usages) / count($usages)),
            'formatted_max' => $this->formatBytes(max($usages)),
            'formatted_min' => $this->formatBytes(min($usages)),
            'data_points' => count($history),
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
}