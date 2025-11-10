# Memory Optimization Guide

## Overview
This guide provides best practices and techniques for optimizing memory usage in PHP applications. Proper memory management is crucial for application performance and scalability.

## Key Memory Management Concepts

### 1. Memory Profiling
Understanding how your application uses memory is the first step in optimization:

- **Current Usage**: `memory_get_usage(true)` - Total memory allocated by PHP
- **Peak Usage**: `memory_get_peak_usage(true)` - Maximum memory allocated during execution
- **Real Usage**: `memory_get_usage(false)` - Memory used by emalloc allocator

### 2. Garbage Collection
PHP's garbage collector automatically frees memory occupied by cyclic references:

```php
// Enable garbage collection
gc_enable();

// Force collection cycle
gc_collect_cycles();

// Get GC status
$status = gc_status();
```

### 3. Memory Leaks
Common causes of memory leaks in PHP:
- Circular references
- Unclosed resources (files, database connections)
- Accumulation of large arrays
- Improper object destruction

## Optimization Techniques

### 1. Object Pooling
Reuse objects instead of creating new ones:

```php
class ObjectPool {
    private array $pool = [];
    
    public function acquire() {
        return !empty($this->pool) ? array_pop($this->pool) : new Object();
    }
    
    public function release($object) {
        $this->pool[] = $object;
    }
}
```

### 2. Lazy Loading
Load resources only when needed:

```php
class LazyLoader {
    private array $loaded = [];
    
    public function get($key, $factory) {
        if (!isset($this->loaded[$key])) {
            $this->loaded[$key] = $factory();
        }
        return $this->loaded[$key];
    }
}
```

### 3. Data Structure Optimization
Choose appropriate data structures:

- Use `SplFixedArray` for large numeric arrays
- Use generators for large datasets
- Unset large variables when no longer needed

### 4. Weak References
Prevent circular references with weak references (PHP 7.4+):

```php
$obj = new stdClass();
$weakRef = WeakReference::create($obj);
// $weakRef->get() returns object or null if collected
```

## Best Practices

### 1. Resource Management
- Always close file handles, database connections, and sockets
- Use try/finally blocks for cleanup
- Implement proper destructors for objects managing resources

### 2. Memory Monitoring
- Set memory limits appropriately
- Monitor memory usage in production
- Implement alerting for memory usage thresholds

### 3. Code Patterns
- Avoid creating unnecessary objects in loops
- Unset large variables when done
- Use static methods for utility functions to avoid object instantiation

## Tools for Memory Analysis

### 1. Xdebug Profiling
Enable profiling to analyze memory usage:

```ini
xdebug.mode=profile
xdebug.start_with_request=trigger
```

### 2. Built-in Functions
Monitor memory usage with built-in functions:

```php
$startMemory = memory_get_usage();
// ... code ...
$endMemory = memory_get_usage();
echo "Memory used: " . ($endMemory - $startMemory) . " bytes";
```

## Performance Considerations

### 1. Memory vs. Performance Trade-offs
- More memory-efficient code may be slower
- Balance memory usage with execution time
- Profile both memory and performance

### 2. Caching Strategies
- Use appropriate caching mechanisms
- Implement cache warming for better performance
- Monitor cache memory usage

## Common Pitfalls to Avoid

1. **Unintentional Variable Retention**
   ```php
   // Bad - $largeData stays in memory
   $largeData = file_get_contents('large_file.txt');
   processData($largeData);
   // Forgot to unset($largeData)
   
   // Good - explicitly unset when done
   $largeData = file_get_contents('large_file.txt');
   processData($largeData);
   unset($largeData);
   ```

2. **Circular References**
   ```php
   // Bad - creates circular reference
   class Parent {
       public $child;
   }
   
   class Child {
       public $parent;
   }
   
   $parent = new Parent();
   $child = new Child();
   $parent->child = $child;
   $child->parent = $parent; // Circular reference
   ```

3. **Accumulation in Loops**
   ```php
   // Bad - accumulates results
   $results = [];
   foreach ($largeDataset as $item) {
       $results[] = processItem($item);
   }
   
   // Better - process in batches
   $batch = [];
   foreach ($largeDataset as $item) {
       $batch[] = processItem($item);
       if (count($batch) >= 1000) {
           saveBatch($batch);
           $batch = []; // Clear memory
       }
   }
   ```

## Conclusion
Effective memory management is essential for building scalable PHP applications. By implementing these techniques and continuously monitoring memory usage, you can significantly improve your application's performance and stability.