<?php

/**
 * Configuration file for Memory Management Challenge
 */

return [
    'app' => [
        'name' => 'Memory Management Challenge',
        'version' => '1.0.0',
        'debug' => true,
    ],
    
    'memory' => [
        'profiling_enabled' => true,
        'leak_detection_threshold' => 10485760, // 10 MB
        'alert_threshold' => 52428800, // 50 MB
        'sampling_rate' => 100, // Check every 100 requests
    ],
    
    'xdebug' => [
        'enabled' => extension_loaded('xdebug'),
        'profiling_enabled' => true,
        'trace_format' => 1,
    ],
    
    'monitoring' => [
        'log_level' => 'INFO',
        'report_interval' => 60, // seconds
        'storage_path' => __DIR__ . '/../storage/reports',
    ],
];