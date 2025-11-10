<?php

namespace App\Utils;

/**
 * Memory Formatter - Utility class for formatting memory values
 */
class MemoryFormatter
{
    /**
     * Format bytes to human readable format
     */
    public static function formatBytes(int $size, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
            $size /= 1024;
        }
        
        return round($size, $precision) . ' ' . $units[$i];
    }
    
    /**
     * Format bytes to detailed memory information
     */
    public static function formatDetailed(int $bytes): array
    {
        return [
            'bytes' => $bytes,
            'formatted' => self::formatBytes($bytes),
            'kilobytes' => round($bytes / 1024, 2),
            'megabytes' => round($bytes / (1024 * 1024), 2),
            'gigabytes' => round($bytes / (1024 * 1024 * 1024), 2),
        ];
    }
    
    /**
     * Convert formatted string back to bytes
     */
    public static function parseBytes(string $formatted): int
    {
        $formatted = trim($formatted);
        $units = ['B' => 1, 'KB' => 1024, 'MB' => 1024 * 1024, 'GB' => 1024 * 1024 * 1024, 'TB' => 1024 * 1024 * 1024 * 1024];
        
        foreach ($units as $unit => $multiplier) {
            if (stripos($formatted, $unit) !== false) {
                $number = floatval(str_replace($unit, '', $formatted));
                return intval($number * $multiplier);
            }
        }
        
        // Assume bytes if no unit specified
        return intval($formatted);
    }
    
    /**
     * Get memory usage comparison
     */
    public static function compareUsage(int $before, int $after): array
    {
        $difference = $after - $before;
        $percentage = $before > 0 ? ($difference / $before) * 100 : 0;
        
        return [
            'before' => self::formatDetailed($before),
            'after' => self::formatDetailed($after),
            'difference' => self::formatDetailed(abs($difference)),
            'percentage_change' => round($percentage, 2),
            'increase' => $difference > 0,
        ];
    }
}