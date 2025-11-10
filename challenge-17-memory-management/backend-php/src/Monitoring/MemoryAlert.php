<?php

namespace App\Monitoring;

/**
 * Memory Alert - Handles memory usage alerts and notifications
 */
class MemoryAlert
{
    private int $threshold;
    private array $alerts = [];
    private bool $enabled;
    
    public function __construct(int $threshold = 52428800, bool $enabled = true) // 50MB default
    {
        $this->threshold = $threshold;
        $this->enabled = $enabled;
    }
    
    /**
     * Check memory usage and trigger alert if threshold exceeded
     */
    public function checkAndAlert(string $context = ''): ?array
    {
        if (!$this->enabled) {
            return null;
        }
        
        $currentUsage = memory_get_usage(true);
        
        if ($currentUsage > $this->threshold) {
            $alert = [
                'id' => uniqid(),
                'timestamp' => time(),
                'context' => $context,
                'usage' => $currentUsage,
                'threshold' => $this->threshold,
                'exceeded_by' => $currentUsage - $this->threshold,
                'formatted_usage' => $this->formatBytes($currentUsage),
                'formatted_threshold' => $this->formatBytes($this->threshold),
                'severity' => $this->calculateSeverity($currentUsage),
            ];
            
            $this->alerts[] = $alert;
            return $alert;
        }
        
        return null;
    }
    
    /**
     * Calculate alert severity based on usage
     */
    private function calculateSeverity(int $usage): string
    {
        $ratio = $usage / $this->threshold;
        
        if ($ratio > 2) {
            return 'critical';
        } elseif ($ratio > 1.5) {
            return 'high';
        } elseif ($ratio > 1.3) {
            return 'medium';
        } else {
            return 'low';
        }
    }
    
    /**
     * Get all alerts
     */
    public function getAlerts(): array
    {
        return $this->alerts;
    }
    
    /**
     * Get alerts by severity
     */
    public function getAlertsBySeverity(string $severity): array
    {
        return array_filter($this->alerts, function($alert) use ($severity) {
            return $alert['severity'] === $severity;
        });
    }
    
    /**
     * Clear alerts
     */
    public function clearAlerts(): void
    {
        $this->alerts = [];
    }
    
    /**
     * Send notification (simplified implementation)
     */
    public function sendNotification(array $alert): void
    {
        // In a real implementation, this would send an email, Slack message, etc.
        error_log("MEMORY ALERT [{$alert['severity']}]: {$alert['formatted_usage']} exceeds threshold {$alert['formatted_threshold']} in context: {$alert['context']}");
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