# Challenge 97: Memory Management Deep Dive

## Description
In this challenge, you'll implement custom memory allocators for PHP and dive deep into PHP's memory management system. Understanding how PHP manages memory is crucial for building high-performance applications and extensions. You'll explore the Zend Memory Manager, implement custom allocators, detect memory leaks, and optimize memory usage patterns.

## Learning Objectives
- Understand PHP's memory management architecture
- Learn about the Zend Memory Manager (ZendMM)
- Implement custom memory allocators
- Detect and fix memory leaks
- Optimize memory usage patterns
- Understand garbage collection mechanisms
- Build memory profiling tools
- Implement memory pooling strategies

## Requirements

### Core Features
1. **Memory Allocator Implementation**
   - Implement custom memory allocators
   - Create memory pools for specific use cases
   - Build slab allocators for fixed-size objects
   - Implement arena allocators for related objects
   - Handle memory alignment requirements

2. **Memory Leak Detection**
   - Build tools to detect memory leaks
   - Implement reference tracking
   - Create memory usage profilers
   - Monitor allocation patterns
   - Generate leak reports

3. **Garbage Collection Optimization**
   - Understand PHP's garbage collection cycle
   - Implement custom GC strategies
   - Optimize circular reference handling
   - Build GC tuning tools
   - Monitor GC performance

4. **Performance Optimization**
   - Implement memory-efficient data structures
   - Optimize allocation patterns
   - Reduce memory fragmentation
   - Build caching mechanisms
   - Implement lazy allocation

### Implementation Details
1. **Custom Allocator Interface**
   ```php
   interface MemoryAllocatorInterface
   {
       public function allocate(int $size): mixed;
       public function deallocate(mixed $ptr): void;
       public function realloc(mixed $ptr, int $newSize): mixed;
       public function getStats(): array;
   }
   ```

2. **Memory Pool Implementation**
   ```php
   class MemoryPool implements MemoryAllocatorInterface
   {
       public function __construct(int $poolSize) { /* ... */ }
       public function allocate(int $size): mixed { /* ... */ }
       public function deallocate(mixed $ptr): void { /* ... */ }
   }
   ```

## Project Structure
```
challenge-97-memory-management/
├── backend-php/
│   ├── src/
│   │   ├── Memory/
│   │   │   ├── AllocatorInterface.php
│   │   │   ├── MemoryManager.php
│   │   │   ├── PoolAllocator.php
│   │   │   ├── SlabAllocator.php
│   │   │   ├── ArenaAllocator.php
│   │   │   └── Exception/
│   │   │       ├── MemoryException.php
│   │   │       └── AllocationException.php
│   │   ├── GarbageCollection/
│   │   │   ├── GarbageCollector.php
│   │   │   ├── ReferenceTracker.php
│   │   │   └── CycleDetector.php
│   │   └── Profiling/
│   │       ├── MemoryProfiler.php
│   │       ├── LeakDetector.php
│   │       └── UsageTracker.php
│   ├── config/
│   ├── public/
│   │   └── index.php
│   ├── tests/
│   ├── composer.json
│   ├── Dockerfile
│   └── README.md
├── frontend-react/
│   ├── src/
│   │   ├── components/
│   │   │   ├── MemoryVisualizer.jsx
│   │   │   ├── LeakDetector.jsx
│   │   │   └── PerformanceChart.jsx
│   │   └── App.jsx
│   ├── package.json
│   ├── vite.config.js
│   ├── Dockerfile
│   └── README.md
├── docker-compose.yml
└── README.md
```

## Setup Instructions

### Prerequisites
- PHP 8.1+
- Composer
- Node.js 16+
- npm or yarn
- Docker (optional, for containerized deployment)
- Xdebug for memory profiling

### Backend Setup
1. Navigate to the [backend-php](file:///c%3A/projects/php-fullstack-challenges/challenge-97-memory-management/backend-php) directory
2. Install PHP dependencies:
   ```bash
   composer install
   ```
3. Enable Xdebug for memory profiling (php.ini):
   ```ini
   zend_extension=xdebug
   xdebug.mode=profile
   xdebug.start_with_request=trigger
   ```
4. Start the development server:
   ```bash
   php public/index.php
   ```

### Frontend Setup
1. Navigate to the [frontend-react](file:///c%3A/projects/php-fullstack-challenges/challenge-97-memory-management/frontend-react) directory
2. Install JavaScript dependencies:
   ```bash
   npm install
   ```
3. Start the development server:
   ```bash
   npm run dev
   ```

### Docker Setup
1. From the challenge root directory, run:
   ```bash
   docker-compose up -d
   ```
2. Access the application at `http://localhost:3000`

## API Endpoints

### Memory Management
- **POST** `/memory/allocate` - Allocate memory using custom allocator
- **POST** `/memory/deallocate` - Deallocate memory
- **GET** `/memory/stats` - Get memory usage statistics
- **POST** `/memory/profile` - Run memory profiling
- **GET** `/memory/leaks` - Detect and report memory leaks

## Implementation Details

### Understanding PHP Memory Management

1. **Zend Memory Manager (ZendMM)**
   PHP uses the Zend Memory Manager for all memory allocations. It provides features like:
   - Memory pooling
   - Garbage collection
   - Memory protection
   - Leak detection

   ```php
   // Example of using emalloc/efree (PHP's internal allocators)
   class ZendMemoryWrapper
   {
       public function allocate(int $size): string
       {
           // In a real extension, you'd use emalloc(size)
           // This is a simplified PHP example
           return str_repeat("\0", $size);
       }
       
       public function reallocate(string $data, int $newSize): string
       {
           // In a real extension, you'd use erealloc(ptr, size)
           return str_pad($data, $newSize, "\0");
       }
   }
   ```

2. **Custom Memory Pool**
   ```php
   class MemoryPool
   {
       private array $chunks = [];
       private int $chunkSize;
       private int $currentChunk = 0;
       private int $currentOffset = 0;
       private array $allocations = [];
       
       public function __construct(int $chunkSize = 1024 * 1024) // 1MB chunks
       {
           $this->chunkSize = $chunkSize;
           $this->chunks[] = str_repeat("\0", $chunkSize);
       }
       
       public function allocate(int $size): string
       {
           // Ensure we have enough space in current chunk
           if ($this->currentOffset + $size > $this->chunkSize) {
               // Move to next chunk or create new one
               $this->currentChunk++;
               $this->currentOffset = 0;
               
               if (!isset($this->chunks[$this->currentChunk])) {
                   $this->chunks[$this->currentChunk] = str_repeat("\0", $this->chunkSize);
               }
           }
           
           // Allocate from current position
           $pointer = "chunk_{$this->currentChunk}_{$this->currentOffset}";
           $this->allocations[$pointer] = [
               'chunk' => $this->currentChunk,
               'offset' => $this->currentOffset,
               'size' => $size
           ];
           
           $this->currentOffset += $size;
           
           return $pointer;
       }
       
       public function deallocate(string $pointer): void
       {
           if (isset($this->allocations[$pointer])) {
               // In a real implementation, we might add this space back to a free list
               // For this example, we'll just remove the tracking
               unset($this->allocations[$pointer]);
           }
       }
       
       public function getStats(): array
       {
           $totalChunks = count($this->chunks);
           $usedMemory = array_sum(array_column($this->allocations, 'size'));
           $totalMemory = $totalChunks * $this->chunkSize;
           
           return [
               'chunks' => $totalChunks,
               'used_memory' => $usedMemory,
               'total_memory' => $totalMemory,
               'utilization' => $totalMemory > 0 ? ($usedMemory / $totalMemory) : 0,
               'allocations' => count($this->allocations)
           ];
       }
   }
   ```

3. **Slab Allocator**
   ```php
   class SlabAllocator
   {
       private array $slabs = [];
       private array $freeLists = [];
       private int $pageSize;
       
       public function __construct(int $pageSize = 4096)
       {
           $this->pageSize = $pageSize;
       }
       
       public function allocate(int $size): string
       {
           // Round up to nearest power of 2 for slab sizing
           $slabSize = $this->getNextPowerOfTwo($size);
           
           // Create slab if it doesn't exist
           if (!isset($this->slabs[$slabSize])) {
               $this->createSlab($slabSize);
           }
           
           // Allocate from free list or create new object
           if (!empty($this->freeLists[$slabSize])) {
               $pointer = array_pop($this->freeLists[$slabSize]);
           } else {
               $pointer = $this->allocateFromSlab($slabSize);
           }
           
           return $pointer;
       }
       
       public function deallocate(string $pointer, int $size): void
       {
           $slabSize = $this->getNextPowerOfTwo($size);
           
           if (!isset($this->freeLists[$slabSize])) {
               $this->freeLists[$slabSize] = [];
           }
           
           $this->freeLists[$slabSize][] = $pointer;
       }
       
       private function createSlab(int $slabSize): void
       {
           // Create a new slab with multiple objects of slabSize
           $objectsPerSlab = intdiv($this->pageSize, $slabSize);
           $this->slabs[$slabSize] = [
               'data' => str_repeat("\0", $this->pageSize),
               'objects' => $objectsPerSlab,
               'free' => $objectsPerSlab
           ];
           
           $this->freeLists[$slabSize] = [];
           for ($i = 0; $i < $objectsPerSlab; $i++) {
               $this->freeLists[$slabSize][] = "slab_{$slabSize}_{$i}";
           }
       }
       
       private function allocateFromSlab(int $slabSize): string
       {
           // Simplified allocation from slab
           return "slab_{$slabSize}_" . uniqid();
       }
       
       private function getNextPowerOfTwo(int $size): int
       {
           if ($size <= 0) return 1;
           if (($size & ($size - 1)) == 0) return $size;
           
           $power = 1;
           while ($power < $size) {
               $power <<= 1;
           }
           return $power;
       }
   }
   ```

4. **Memory Leak Detection**
   ```php
   class LeakDetector
   {
       private array $allocations = [];
       private int $allocationCounter = 0;
       
       public function trackAllocation(string $description, int $size): string
       {
           $id = "alloc_" . ++$this->allocationCounter;
           $this->allocations[$id] = [
               'description' => $description,
               'size' => $size,
               'allocated_at' => microtime(true),
               'backtrace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 10)
           ];
           
           return $id;
       }
       
       public function trackDeallocation(string $id): void
       {
           unset($this->allocations[$id]);
       }
       
       public function getLeaks(): array
       {
           return $this->allocations;
       }
       
       public function getLeakReport(): string
       {
           if (empty($this->allocations)) {
               return "No memory leaks detected.";
           }
           
           $report = "Memory Leak Report:\n";
           $report .= "==================\n";
           $totalLeaked = 0;
           
           foreach ($this->allocations as $id => $info) {
               $report .= "Leak ID: {$id}\n";
               $report .= "Description: {$info['description']}\n";
               $report .= "Size: {$info['size']} bytes\n";
               $report .= "Allocated: " . date('Y-m-d H:i:s', $info['allocated_at']) . "\n";
               $report .= "Location: " . $this->formatBacktrace($info['backtrace']) . "\n\n";
               $totalLeaked += $info['size'];
           }
           
           $report .= "Total leaked memory: {$totalLeaked} bytes\n";
           
           return $report;
       }
       
       private function formatBacktrace(array $backtrace): string
       {
           $formatted = [];
           foreach ($backtrace as $frame) {
               $file = $frame['file'] ?? 'unknown';
               $line = $frame['line'] ?? 'unknown';
               $function = $frame['function'] ?? 'unknown';
               $formatted[] = "{$file}:{$line} in {$function}()";
           }
           return implode(" <- ", $formatted);
       }
   }
   ```

5. **Garbage Collection Monitor**
   ```php
   class GarbageCollectionMonitor
   {
       private array $stats = [];
       
       public function __construct()
       {
           // Enable garbage collection monitoring
           gc_enable();
       }
       
       public function collectGarbage(): array
       {
           $before = $this->getGCStats();
           $collected = gc_collect_cycles();
           $after = $this->getGCStats();
           
           $this->stats[] = [
               'timestamp' => microtime(true),
               'collected' => $collected,
               'before' => $before,
               'after' => $after,
               'memory_before' => memory_get_usage(true),
               'memory_after' => memory_get_usage(true)
           ];
           
           return end($this->stats);
       }
       
       public function getGCStats(): array
       {
           if (function_exists('gc_status')) {
               return gc_status();
           }
           
           return [
               'runs' => gc_runs(),
               'collected' => gc_collected(),
               'threshold' => gc_threshold(),
               'roots' => gc_root_buffer_get()
           ];
       }
       
       public function getPerformanceStats(): array
       {
           $totalRuns = 0;
           $totalCollected = 0;
           $totalTime = 0;
           
           foreach ($this->stats as $stat) {
               $totalRuns += $stat['after']['runs'] - $stat['before']['runs'];
               $totalCollected += $stat['collected'];
           }
           
           return [
               'total_runs' => $totalRuns,
               'total_collected' => $totalCollected,
               'average_collection_rate' => $totalRuns > 0 ? $totalCollected / $totalRuns : 0,
               'history' => $this->stats
           ];
       }
   }
   ```

6. **Memory Profiler**
   ```php
   class MemoryProfiler
   {
       private array $checkpoints = [];
       
       public function checkpoint(string $name): void
       {
           $this->checkpoints[$name] = [
               'memory_usage' => memory_get_usage(true),
               'real_memory_usage' => memory_get_usage(false),
               'peak_memory_usage' => memory_get_peak_usage(true),
               'timestamp' => microtime(true)
           ];
       }
       
       public function getProfile(): array
       {
           $profile = [];
           $previous = null;
           
           foreach ($this->checkpoints as $name => $data) {
               $profile[$name] = $data;
               
               if ($previous !== null) {
                   $profile[$name]['diff'] = [
                       'memory' => $data['memory_usage'] - $previous['memory_usage'],
                       'real_memory' => $data['real_memory_usage'] - $previous['real_memory_usage'],
                       'time' => $data['timestamp'] - $previous['timestamp']
                   ];
               }
               
               $previous = $data;
           }
           
           return $profile;
       }
       
       public function getMemoryUsageReport(): string
       {
           $report = "Memory Usage Report:\n";
           $report .= "==================\n";
           
           $previous = null;
           foreach ($this->checkpoints as $name => $data) {
               $report .= "\nCheckpoint: {$name}\n";
               $report .= "Memory Usage: " . $this->formatBytes($data['memory_usage']) . "\n";
               $report .= "Real Memory Usage: " . $this->formatBytes($data['real_memory_usage']) . "\n";
               $report .= "Peak Memory Usage: " . $this->formatBytes($data['peak_memory_usage']) . "\n";
               
               if ($previous !== null) {
                   $diffMemory = $data['memory_usage'] - $previous['memory_usage'];
                   $diffTime = $data['timestamp'] - $previous['timestamp'];
                   $report .= "Change: " . ($diffMemory >= 0 ? '+' : '') . $this->formatBytes($diffMemory) . " in " . number_format($diffTime * 1000, 2) . "ms\n";
               }
               
               $previous = $data;
           }
           
           return $report;
       }
       
       private function formatBytes(int $bytes): string
       {
           $units = ['B', 'KB', 'MB', 'GB', 'TB'];
           $bytes = max($bytes, 0);
           $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
           $pow = min($pow, count($units) - 1);
           
           $bytes /= pow(1024, $pow);
           
           return round($bytes, 2) . ' ' . $units[$pow];
       }
   }
   ```

### Frontend Interface
The React frontend should:
1. Visualize memory allocation patterns
2. Show leak detection results
3. Display garbage collection statistics
4. Provide memory profiling charts
5. Offer educational content about memory management

## Evaluation Criteria
1. **Correctness** (30%)
   - Proper implementation of memory allocators
   - Accurate leak detection
   - Correct GC monitoring

2. **Performance** (25%)
   - Efficient memory allocation
   - Minimal overhead from monitoring
   - Proper optimization techniques

3. **Depth of Understanding** (20%)
   - Comprehensive memory management concepts
   - Advanced allocator implementations
   - Thorough leak detection

4. **Code Quality** (15%)
   - Clean, well-organized implementation
   - Proper error handling
   - Comprehensive documentation

5. **Educational Value** (10%)
   - Clear explanations of memory concepts
   - Practical examples and use cases
   - Interactive demonstrations

## Resources
1. [PHP Memory Management](https://www.php.net/manual/en/internals2.memory.php)
2. [Zend Memory Manager](https://www.php.net/manual/en/internals2.memory.management.php)
3. [Garbage Collection in PHP](https://www.php.net/manual/en/features.gc.php)
4. [Xdebug Profiling](https://xdebug.org/docs/profiler)
5. [Memory Allocation Strategies](https://en.wikipedia.org/wiki/Memory_management)
6. [Slab Allocation](https://en.wikipedia.org/wiki/Slab_allocation)
7. [Memory Leak Detection](https://en.wikipedia.org/wiki/Memory_leak)

## Stretch Goals
1. Implement a memory defragmenter
2. Create a visual memory map
3. Build a memory compression system
4. Implement custom GC algorithms
5. Create memory usage prediction models
6. Develop advanced profiling tools
7. Implement memory sharing between processes