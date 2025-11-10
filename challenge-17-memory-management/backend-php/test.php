<?php

// Manually include our classes since we can't use Composer
require_once 'src/Memory/Profiler.php';
require_once 'src/Memory/Monitor.php';
require_once 'src/Utils/MemoryFormatter.php';

use App\Memory\Profiler;
use App\Memory\Monitor;
use App\Utils\MemoryFormatter;

// Test the profiler
$profiler = new Profiler();
$profiler->snapshot('Start');

// Create some data to consume memory
$data = range(1, 10000);
$profiler->snapshot('After creating data');

// Test memory formatting
$memory = memory_get_usage(true);
$formatted = MemoryFormatter::formatBytes($memory);

echo "Current memory usage: " . $formatted . "\n";

// Test monitor
$monitor = new Monitor($profiler, 50 * 1024 * 1024); // 50MB threshold
$status = $monitor->getStatus();

echo "Memory status: " . ($status['alert_triggered'] ? 'ALERT' : 'OK') . "\n";
echo "Usage: " . $status['formatted_usage'] . "\n";
echo "Threshold: " . $status['formatted_threshold'] . "\n";

// Show profiler stats
$stats = $profiler->getStats();
if (!empty($stats)) {
    echo "Average memory usage: " . $stats['formatted_average'] . "\n";
    echo "Peak memory usage: " . $stats['formatted_max'] . "\n";
}

echo "Test completed successfully!\n";