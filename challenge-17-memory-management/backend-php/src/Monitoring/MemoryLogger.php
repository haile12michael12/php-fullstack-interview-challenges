<?php

namespace App\Monitoring;

/**
 * Memory Logger - Logs memory usage for monitoring and analysis
 */
class MemoryLogger
{
    private string $logFile;
    private bool $enabled;
    
    public function __construct(string $logFile = 'memory.log', bool $enabled = true)
    {
        $this->logFile = $logFile;
        $this->enabled = $enabled;
    }
    
    /**
     * Log current memory usage
     */
    public function logUsage(string $context = '', array $additionalData = []): void
    {
        if (!$this->enabled) {
            return;
        }
        
        $data = [
            'timestamp' => date('Y-m-d H:i:s'),
            'context' => $context,
            'memory_usage' => memory_get_usage(true),
            'memory_peak' => memory_get_peak_usage(true),
            'formatted_usage' => $this->formatBytes(memory_get_usage(true)),
            'formatted_peak' => $this->formatBytes(memory_get_peak_usage(true)),
        ];
        
        // Merge additional data
        $data = array_merge($data, $additionalData);
        
        // Write to log file
        $logEntry = json_encode($data) . PHP_EOL;
        file_put_contents($this->logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }
    
    /**
     * Log memory alert
     */
    public function logAlert(string $message, int $threshold, array $additionalData = []): void
    {
        if (!$this->enabled) {
            return;
        }
        
        $data = [
            'timestamp' => date('Y-m-d H:i:s'),
            'type' => 'alert',
            'message' => $message,
            'current_usage' => memory_get_usage(true),
            'threshold' => $threshold,
            'formatted_usage' => $this->formatBytes(memory_get_usage(true)),
            'formatted_threshold' => $this->formatBytes($threshold),
        ];
        
        // Merge additional data
        $data = array_merge($data, $additionalData);
        
        // Write to log file
        $logEntry = json_encode($data) . PHP_EOL;
        file_put_contents($this->logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }
    
    /**
     * Get log entries
     */
    public function getLogs(int $limit = 100): array
    {
        if (!file_exists($this->logFile)) {
            return [];
        }
        
        $lines = file($this->logFile, FILE_IGNORE_NEW_LINES);
        $entries = [];
        
        // Get last N entries
        $start = max(0, count($lines) - $limit);
        for ($i = $start; $i < count($lines); $i++) {
            $entry = json_decode($lines[$i], true);
            if ($entry) {
                $entries[] = $entry;
            }
        }
        
        return array_reverse($entries); // Most recent first
    }
    
    /**
     * Clear log file
     */
    public function clearLogs(): void
    {
        if (file_exists($this->logFile)) {
            unlink($this->logFile);
        }
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