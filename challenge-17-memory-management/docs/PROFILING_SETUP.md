# Profiling Setup Guide

## Overview
This guide explains how to set up and use memory profiling tools for PHP applications. Proper profiling is essential for identifying memory issues and optimizing performance.

## Xdebug Installation and Configuration

### 1. Installation
Install Xdebug using PECL:

```bash
pecl install xdebug
```

Or install via package manager (Ubuntu/Debian):

```bash
sudo apt-get install php-xdebug
```

### 2. Configuration
Add the following to your php.ini file:

```ini
zend_extension=xdebug

; Enable profiling
xdebug.mode=profile,debug
xdebug.start_with_request=trigger

; Profiling settings
xdebug.output_dir=/tmp/profiling
xdebug.profiler_output_name=cachegrind.out.%p
```

### 3. Verification
Check if Xdebug is properly installed:

```bash
php -v
# Should show Xdebug in the output

php -m | grep xdebug
# Should list xdebug
```

## Using Xdebug Profiling

### 1. Triggering Profiling
Start profiling by setting the XDEBUG_PROFILE cookie or GET parameter:

```
http://localhost:8000/api/memory/profile?XDEBUG_PROFILE
```

Or use a browser extension like "Xdebug Helper" to trigger profiling.

### 2. Profiling Output
Profile files are generated in the configured output directory (default: system temp directory). Files are named like `cachegrind.out.PID`.

### 3. Analyzing Profiles
Use tools like KCacheGrind (Linux), QCacheGrind (Windows), or Webgrind to analyze profile files.

## Built-in Memory Profiling Tools

### 1. Memory Usage Functions
PHP provides several built-in functions for memory monitoring:

```php
// Get current memory usage
echo memory_get_usage(); // Returns bytes

// Get peak memory usage
echo memory_get_peak_usage();

// Get usage with memory management overhead
echo memory_get_usage(true);
```

### 2. Garbage Collection Monitoring
Monitor garbage collection statistics:

```php
$stats = gc_status();
print_r($stats);

// Key metrics:
// - runs: Number of GC cycles
// - collected: Number of objects collected
// - threshold: GC threshold
// - roots: Number of root buffers
```

## Custom Profiling Implementation

### 1. Memory Snapshot Class
Create a custom profiler for application-specific monitoring:

```php
class MemoryProfiler {
    private array $snapshots = [];
    
    public function snapshot($label = '') {
        $this->snapshots[] = [
            'label' => $label,
            'time' => microtime(true),
            'memory' => memory_get_usage(true)
        ];
    }
    
    public function getDifference($start, $end) {
        return $this->snapshots[$end]['memory'] - $this->snapshots[$start]['memory'];
    }
}
```

### 2. Real-time Monitoring
Implement real-time memory monitoring:

```php
class MemoryMonitor {
    private $threshold;
    
    public function __construct($threshold = 50 * 1024 * 1024) { // 50MB
        $this->threshold = $threshold;
    }
    
    public function checkMemory() {
        $usage = memory_get_usage(true);
        if ($usage > $this->threshold) {
            $this->triggerAlert($usage);
        }
    }
    
    private function triggerAlert($usage) {
        error_log("Memory usage alert: " . $this->formatBytes($usage));
    }
    
    private function formatBytes($bytes) {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
```

## Performance Profiling Tools

### 1. Blackfire
Professional PHP profiler with memory analysis:

```bash
# Install Blackfire agent and PHP extension
# Configure credentials
# Run profiling
blackfire run php your-script.php
```

### 2. Tideways
Application performance monitoring with memory profiling:

```php
// Start profiling
tideways_enable(TIDEWAYS_FLAGS_MEMORY);

// Your code here

// Stop and get data
$data = tideways_disable();
```

## Profiling Best Practices

### 1. Baseline Measurements
- Establish baseline memory usage for normal operations
- Profile during peak usage periods
- Compare measurements over time

### 2. Profiling Strategy
- Profile specific operations, not entire applications
- Use representative data sets
- Profile in environments similar to production

### 3. Data Analysis
- Look for memory growth patterns
- Identify functions with high memory consumption
- Compare memory usage before and after optimizations

## Troubleshooting

### 1. Profiling Not Working
- Verify Xdebug installation
- Check php.ini configuration
- Ensure web server has write permissions to output directory

### 2. Large Profile Files
- Set appropriate profiling triggers
- Limit profiling to specific requests
- Regularly clean up old profile files

### 3. Performance Impact
- Profiling adds overhead, use only in development
- Disable profiling in production environments
- Use sampling for continuous monitoring

## Advanced Profiling Techniques

### 1. Custom Metrics
Track application-specific metrics:

```php
class ApplicationProfiler {
    private $metrics = [];
    
    public function startMetric($name) {
        $this->metrics[$name] = [
            'start_memory' => memory_get_usage(true),
            'start_time' => microtime(true)
        ];
    }
    
    public function endMetric($name) {
        $this->metrics[$name]['end_memory'] = memory_get_usage(true);
        $this->metrics[$name]['end_time'] = microtime(true);
        $this->metrics[$name]['memory_diff'] = 
            $this->metrics[$name]['end_memory'] - $this->metrics[$name]['start_memory'];
        $this->metrics[$name]['time_diff'] = 
            $this->metrics[$name]['end_time'] - $this->metrics[$name]['start_time'];
    }
}
```

### 2. Memory Leak Detection
Implement leak detection mechanisms:

```php
class LeakDetector {
    private $trackedObjects = [];
    
    public function track($object, $label) {
        $this->trackedObjects[spl_object_hash($object)] = [
            'object' => $object,
            'label' => $label,
            'time' => time()
        ];
    }
    
    public function checkLeaks() {
        gc_collect_cycles();
        foreach ($this->trackedObjects as $hash => $info) {
            if (!array_key_exists($hash, $this->trackedObjects)) {
                // Object was garbage collected
                unset($this->trackedObjects[$hash]);
            }
        }
    }
}
```

## Conclusion
Proper profiling setup is crucial for effective memory management. By using the right tools and techniques, you can identify memory issues, optimize performance, and build more efficient PHP applications.