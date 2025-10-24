# Challenge 91: Asynchronous Programming

## Description
In this challenge, you'll implement asynchronous programming patterns and techniques to build responsive, scalable applications that can handle concurrent operations efficiently. Asynchronous programming is essential for creating high-performance applications that can manage multiple tasks without blocking execution.

## Learning Objectives
- Understand asynchronous programming concepts and patterns
- Implement promises and futures
- Create async/await patterns
- Handle concurrent operations and parallel processing
- Manage error handling in asynchronous code
- Implement event loops and callbacks
- Build reactive programming patterns

## Requirements
Create an asynchronous programming system with the following features:

1. **Promise Implementation**:
   - Promise creation and chaining
   - Promise resolution and rejection
   - Promise composition and combination
   - Promise error handling
   - Promise utilities and helpers
   - Promise performance optimization

2. **Async/Await Patterns**:
   - Async function definition
   - Await expression handling
   - Async context management
   - Async error propagation
   - Async generator functions
   - Async iterator patterns

3. **Concurrency Management**:
   - Concurrent task execution
   - Parallel processing capabilities
   - Task scheduling and prioritization
   - Resource limiting and throttling
   - Task cancellation and timeout
   - Race conditions and synchronization

4. **Advanced Features**:
   - Event loop implementation
   - Reactive programming patterns
   - Stream processing
   - Backpressure handling
   - Memory management and garbage collection
   - Performance monitoring and optimization

## Features to Implement
- [ ] Promise creation and chaining
- [ ] Promise resolution and rejection
- [ ] Promise composition and combination
- [ ] Async function definition
- [ ] Await expression handling
- [ ] Concurrent task execution
- [ ] Parallel processing capabilities
- [ ] Task scheduling and prioritization
- [ ] Task cancellation and timeout
- [ ] Event loop implementation
- [ ] Reactive programming patterns
- [ ] Stream processing

## Project Structure
```
backend-php/
├── src/
│   ├── Async/
│   │   ├── Promise.php
│   │   ├── Async.php
│   │   ├── EventLoop.php
│   │   ├── TaskScheduler.php
│   │   ├── ConcurrencyManager.php
│   │   └── Reactive/
│   │       ├── Observable.php
│   │       ├── Stream.php
│   │       └── Subject.php
│   ├── Promises/
│   │   ├── PromiseInterface.php
│   │   ├── PromiseResolver.php
│   │   ├── PromiseCombinators.php
│   │   └── PromiseUtilities.php
│   ├── AsyncFunctions/
│   │   ├── AsyncFunction.php
│   │   ├── Awaitable.php
│   │   ├── AsyncContext.php
│   │   └── AsyncGenerator.php
│   └── Services/
├── public/
│   └── index.php
├── async/
│   ├── examples/
│   │   ├── promise-examples.php
│   │   ├── async-await-examples.php
│   │   └── concurrency-examples.php
│   ├── tests/
│   │   └── async-tests/
│   └── benchmarks/
├── storage/
│   └── async/
├── config/
│   └── async.php
└── vendor/

frontend-react/
├── src/
│   ├── api/
│   │   └── async.js
│   ├── components/
│   │   ├── Dashboard/
│   │   │   ├── AsyncOverview.jsx
│   │   │   ├── PromiseChain.jsx
│   │   │   └── ConcurrencyMonitor.jsx
│   │   ├── Examples/
│   │   │   ├── PromiseExample.jsx
│   │   │   ├── AsyncAwaitExample.jsx
│   │   │   └── ConcurrencyExample.jsx
│   │   └── Monitoring/
│   │       ├── PerformanceMetrics.jsx
│   │       └── TaskQueue.jsx
│   ├── App.jsx
│   └── main.jsx
├── public/
└── package.json
```

## Setup Instructions
1. Navigate to the `backend-php` directory
2. Run `composer install` to install dependencies
3. Configure your web server to point to the `public` directory
4. Start the development server with `php -S localhost:8000 -t public`
5. Navigate to the `frontend-react` directory
6. Run `npm install` to install dependencies
7. Start the development server with `npm run dev`

## Promise Implementation

### Promise Class
Implement a Promise-like structure:
```php
class Promise
{
    private $state = 'pending'; // pending, fulfilled, rejected
    private $value;
    private $reason;
    private $onFulfilledCallbacks = [];
    private $onRejectedCallbacks = [];
    
    public function __construct(callable $executor = null)
    {
        if ($executor) {
            try {
                $executor(
                    [$this, 'resolve'],
                    [$this, 'reject']
                );
            } catch (Exception $e) {
                $this->reject($e);
            }
        }
    }
    
    public function then(callable $onFulfilled = null, callable $onRejected = null)
    {
        $promise = new static();
        
        if ($this->state === 'fulfilled') {
            $this->handleFulfilled($onFulfilled, $promise);
        } elseif ($this->state === 'rejected') {
            $this->handleRejected($onRejected, $promise);
        } else {
            $this->onFulfilledCallbacks[] = function($value) use ($onFulfilled, $promise) {
                $this->handleFulfilled($onFulfilled, $promise, $value);
            };
            
            $this->onRejectedCallbacks[] = function($reason) use ($onRejected, $promise) {
                $this->handleRejected($onRejected, $promise, $reason);
            };
        }
        
        return $promise;
    }
    
    public function catch(callable $onRejected)
    {
        return $this->then(null, $onRejected);
    }
    
    public function finally(callable $onFinally)
    {
        return $this->then(
            function($value) use ($onFinally) {
                $onFinally();
                return $value;
            },
            function($reason) use ($onFinally) {
                $onFinally();
                throw $reason;
            }
        );
    }
    
    public function resolve($value = null)
    {
        if ($this->state !== 'pending') {
            return;
        }
        
        if ($value instanceof Promise) {
            $value->then(
                [$this, 'resolve'],
                [$this, 'reject']
            );
            return;
        }
        
        $this->state = 'fulfilled';
        $this->value = $value;
        
        foreach ($this->onFulfilledCallbacks as $callback) {
            $callback($value);
        }
    }
    
    public function reject($reason = null)
    {
        if ($this->state !== 'pending') {
            return;
        }
        
        $this->state = 'rejected';
        $this->reason = $reason;
        
        foreach ($this->onRejectedCallbacks as $callback) {
            $callback($reason);
        }
    }
    
    private function handleFulfilled($onFulfilled, $promise, $value = null)
    {
        if ($onFulfilled === null) {
            $promise->resolve($value ?? $this->value);
            return;
        }
        
        try {
            $result = $onFulfilled($value ?? $this->value);
            $promise->resolve($result);
        } catch (Exception $e) {
            $promise->reject($e);
        }
    }
    
    private function handleRejected($onRejected, $promise, $reason = null)
    {
        if ($onRejected === null) {
            $promise->reject($reason ?? $this->reason);
            return;
        }
        
        try {
            $result = $onRejected($reason ?? $this->reason);
            $promise->resolve($result);
        } catch (Exception $e) {
            $promise->reject($e);
        }
    }
    
    public static function resolve($value = null)
    {
        $promise = new static();
        $promise->resolve($value);
        return $promise;
    }
    
    public static function reject($reason = null)
    {
        $promise = new static();
        $promise->reject($reason);
        return $promise;
    }
}
```

### Promise Combinators
Combine multiple promises:
```php
class PromiseCombinators
{
    public static function all(array $promises)
    {
        if (empty($promises)) {
            return Promise::resolve([]);
        }
        
        $results = [];
        $remaining = count($promises);
        $promise = new Promise();
        
        foreach ($promises as $index => $p) {
            $p->then(
                function($value) use ($index, &$results, &$remaining, $promise) {
                    $results[$index] = $value;
                    $remaining--;
                    
                    if ($remaining === 0) {
                        $promise->resolve(array_values($results));
                    }
                },
                function($reason) use ($promise) {
                    $promise->reject($reason);
                }
            );
        }
        
        return $promise;
    }
    
    public static function race(array $promises)
    {
        if (empty($promises)) {
            return Promise::reject(new Exception('No promises provided'));
        }
        
        $promise = new Promise();
        
        foreach ($promises as $p) {
            $p->then(
                function($value) use ($promise) {
                    $promise->resolve($value);
                },
                function($reason) use ($promise) {
                    $promise->reject($reason);
                }
            );
        }
        
        return $promise;
    }
    
    public static function any(array $promises)
    {
        if (empty($promises)) {
            return Promise::reject(new Exception('No promises provided'));
        }
        
        $errors = [];
        $remaining = count($promises);
        $promise = new Promise();
        
        foreach ($promises as $index => $p) {
            $p->then(
                function($value) use ($promise) {
                    $promise->resolve($value);
                },
                function($reason) use ($index, &$errors, &$remaining, $promise) {
                    $errors[$index] = $reason;
                    $remaining--;
                    
                    if ($remaining === 0) {
                        $promise->reject(new Exception('All promises rejected'));
                    }
                }
            );
        }
        
        return $promise;
    }
    
    public static function allSettled(array $promises)
    {
        if (empty($promises)) {
            return Promise::resolve([]);
        }
        
        $results = [];
        $remaining = count($promises);
        $promise = new Promise();
        
        foreach ($promises as $index => $p) {
            $p->then(
                function($value) use ($index, &$results, &$remaining, $promise) {
                    $results[$index] = ['status' => 'fulfilled', 'value' => $value];
                    $remaining--;
                    
                    if ($remaining === 0) {
                        $promise->resolve(array_values($results));
                    }
                },
                function($reason) use ($index, &$results, &$remaining, $promise) {
                    $results[$index] = ['status' => 'rejected', 'reason' => $reason];
                    $remaining--;
                    
                    if ($remaining === 0) {
                        $promise->resolve(array_values($results));
                    }
                }
            );
        }
        
        return $promise;
    }
}
```

## Async/Await Implementation

### Async Function Decorator
Enable async/await syntax:
```php
class Async
{
    public static function async(callable $function)
    {
        return function(...$args) use ($function) {
            $generator = call_user_func_array($function, $args);
            
            if (!$generator instanceof Generator) {
                throw new Exception('Async function must return a Generator');
            }
            
            return self::awaitGenerator($generator);
        };
    }
    
    private static function awaitGenerator(Generator $generator)
    {
        $promise = new Promise();
        
        $resolve = function($value) use (&$resolve, &$reject, $generator, $promise) {
            try {
                if ($generator->valid()) {
                    $next = $generator->current();
                    
                    if ($next instanceof Promise) {
                        $next->then($resolve, $reject);
                    } else {
                        $generator->send($next);
                        $resolve($next);
                    }
                } else {
                    $promise->resolve($generator->getReturn());
                }
            } catch (Exception $e) {
                $promise->reject($e);
            }
        };
        
        $reject = function($reason) use ($promise) {
            $promise->reject($reason);
        };
        
        $resolve(null);
        
        return $promise;
    }
    
    public static function await($promise)
    {
        // This would be used within async functions
        // In practice, this requires PHP generator delegation
        return yield $promise;
    }
}

// Usage example:
$asyncFunction = Async::async(function($url) {
    $response = yield self::fetch($url);
    $data = yield self::parseJson($response);
    return $data;
});
```

## Concurrency Management

### Task Scheduler
Manage concurrent task execution:
```php
class TaskScheduler
{
    private $maxConcurrency;
    private $runningTasks = [];
    private $taskQueue = [];
    private $completedTasks = [];
    
    public function __construct($maxConcurrency = 5)
    {
        $this->maxConcurrency = $maxConcurrency;
    }
    
    public function addTask(callable $task, $priority = 0)
    {
        $taskId = uniqid('task_');
        
        $taskWrapper = [
            'id' => $taskId,
            'task' => $task,
            'priority' => $priority,
            'created_at' => microtime(true)
        ];
        
        // Add to queue sorted by priority
        $this->taskQueue[] = $taskWrapper;
        usort($this->taskQueue, function($a, $b) {
            return $b['priority'] <=> $a['priority'];
        });
        
        $this->processQueue();
        
        return $taskId;
    }
    
    public function runTasks(array $tasks)
    {
        $promises = [];
        
        foreach ($tasks as $task) {
            $promises[] = $this->addTask($task);
        }
        
        return PromiseCombinators::all($promises);
    }
    
    private function processQueue()
    {
        while (count($this->runningTasks) < $this->maxConcurrency && !empty($this->taskQueue)) {
            $taskWrapper = array_shift($this->taskQueue);
            $this->executeTask($taskWrapper);
        }
    }
    
    private function executeTask($taskWrapper)
    {
        $taskId = $taskWrapper['id'];
        $task = $taskWrapper['task'];
        
        $this->runningTasks[$taskId] = [
            'wrapper' => $taskWrapper,
            'started_at' => microtime(true)
        ];
        
        try {
            $result = $task();
            
            if ($result instanceof Promise) {
                $result->then(
                    function($value) use ($taskId) {
                        $this->completeTask($taskId, $value);
                    },
                    function($reason) use ($taskId) {
                        $this->failTask($taskId, $reason);
                    }
                );
            } else {
                $this->completeTask($taskId, $result);
            }
        } catch (Exception $e) {
            $this->failTask($taskId, $e);
        }
    }
    
    private function completeTask($taskId, $result)
    {
        if (!isset($this->runningTasks[$taskId])) {
            return;
        }
        
        $taskInfo = $this->runningTasks[$taskId];
        unset($this->runningTasks[$taskId]);
        
        $this->completedTasks[$taskId] = [
            'wrapper' => $taskInfo['wrapper'],
            'result' => $result,
            'completed_at' => microtime(true),
            'duration' => microtime(true) - $taskInfo['started_at']
        ];
        
        $this->processQueue();
    }
    
    private function failTask($taskId, $error)
    {
        if (!isset($this->runningTasks[$taskId])) {
            return;
        }
        
        $taskInfo = $this->runningTasks[$taskId];
        unset($this->runningTasks[$taskId]);
        
        $this->completedTasks[$taskId] = [
            'wrapper' => $taskInfo['wrapper'],
            'error' => $error,
            'failed_at' => microtime(true),
            'duration' => microtime(true) - $taskInfo['started_at']
        ];
        
        $this->processQueue();
    }
    
    public function getTaskStatus($taskId)
    {
        if (isset($this->runningTasks[$taskId])) {
            return [
                'status' => 'running',
                'started_at' => $this->runningTasks[$taskId]['started_at']
            ];
        }
        
        if (isset($this->completedTasks[$taskId])) {
            $task = $this->completedTasks[$taskId];
            return [
                'status' => isset($task['error']) ? 'failed' : 'completed',
                'result' => $task['result'] ?? null,
                'error' => $task['error'] ?? null,
                'duration' => $task['duration']
            ];
        }
        
        return ['status' => 'pending'];
    }
    
    public function cancelTask($taskId)
    {
        // Remove from queue if pending
        $this->taskQueue = array_filter($this->taskQueue, function($task) use ($taskId) {
            return $task['id'] !== $taskId;
        });
        
        // Cancel running task if possible
        if (isset($this->runningTasks[$taskId])) {
            unset($this->runningTasks[$taskId]);
        }
    }
}
```

## Configuration Example
```php
[
    'promises' => [
        'implementation' => 'native', // native, react, amphp
        'timeout' => 30, // seconds
        'max_recursion' => 100
    ],
    'async' => [
        'max_concurrency' => 10,
        'task_timeout' => 60, // seconds
        'queue_size' => 1000,
        'priority_levels' => [
            'low' => 1,
            'normal' => 5,
            'high' => 10,
            'critical' => 20
        ]
    ],
    'event_loop' => [
        'implementation' => 'react', // react, amphp, swoole
        'tick_interval' => 0.001, // seconds
        'max_ticks_per_cycle' => 1000
    ],
    'concurrency' => [
        'thread_pool_size' => 4,
        'process_pool_size' => 2,
        'resource_limits' => [
            'memory_limit' => '128M',
            'execution_time' => 300 // seconds
        ]
    ],
    'reactive' => [
        'backpressure_strategy' => 'buffer', // buffer, drop, error
        'buffer_size' => 1000,
        'scheduler' => 'immediate' // immediate, async, queued
    ],
    'monitoring' => [
        'enabled' => true,
        'metrics' => [
            'promise_creation_rate' => true,
            'async_task_completion' => true,
            'concurrency_utilization' => true,
            'memory_usage' => true
        ],
        'logging' => [
            'level' => 'info',
            'handlers' => ['file', 'console']
        ]
    ]
]
```

## API Endpoints
```
# Asynchronous Programming Management
POST   /async/promise                # Create new promise
GET    /async/promise/{id}           # Get promise status
POST   /async/promise/{id}/resolve    # Resolve promise
POST   /async/promise/{id}/reject     # Reject promise
GET    /async/promises               # List all promises
POST   /async/promises/combinators    # Use promise combinators
POST   /async/async-function          # Execute async function
GET    /async/tasks                  # List async tasks
POST   /async/tasks                  # Create new task
GET    /async/tasks/{id}             # Get task status
POST   /async/tasks/{id}/cancel       # Cancel task
GET    /async/scheduler              # Get scheduler status
POST   /async/scheduler/config        # Update scheduler config
GET    /async/monitoring/metrics      # Get async metrics
GET    /async/monitoring/performance  # Get performance data
POST   /async/examples/run            # Run async examples
GET    /async/examples/list           # List async examples
```

## Promise Chain Response
```json
{
  "promise": {
    "id": "promise_5f4d4a4b4c4d4e4f50515253",
    "state": "fulfilled",
    "value": {
      "data": "processed result",
      "timestamp": "2023-01-01T10:00:00Z"
    },
    "chain": [
      {
        "step": 1,
        "type": "then",
        "result": "initial data",
        "duration": 0.001
      },
      {
        "step": 2,
        "type": "then",
        "result": "processed data",
        "duration": 0.002
      },
      {
        "step": 3,
        "type": "finally",
        "duration": 0.0005
      }
    ],
    "created_at": "2023-01-01T09:59:59Z",
    "resolved_at": "2023-01-01T10:00:00Z",
    "total_duration": 0.0035
  }
}
```

## Task Scheduler Response
```json
{
  "scheduler": {
    "max_concurrency": 5,
    "running_tasks": 3,
    "pending_tasks": 2,
    "completed_tasks": 15,
    "failed_tasks": 1,
    "tasks": [
      {
        "id": "task_5f4d4a4b4c4d4e4f50515253",
        "status": "running",
        "priority": 5,
        "started_at": "2023-01-01T10:00:00Z",
        "duration": 2.5
      },
      {
        "id": "task_5f4d4a4b4c4d4e4f50515254",
        "status": "pending",
        "priority": 10,
        "created_at": "2023-01-01T09:59:30Z"
      }
    ],
    "utilization": 60,
    "last_updated": "2023-01-01T10:00:00Z"
  }
}
```

## Performance Metrics Response
```json
{
  "metrics": {
    "promises": {
      "created": 1250,
      "resolved": 1200,
      "rejected": 50,
      "pending": 0,
      "average_resolution_time": 0.0025,
      "rejection_rate": 0.04
    },
    "async_tasks": {
      "executed": 850,
      "completed": 820,
      "failed": 30,
      "average_execution_time": 0.15,
      "concurrency_utilization": 75
    },
    "memory": {
      "current_usage": "32MB",
      "peak_usage": "48MB",
      "garbage_collections": 15
    },
    "event_loop": {
      "ticks_per_second": 1200,
      "average_tick_time": 0.0008,
      "max_tick_time": 0.005
    }
  },
  "timestamp": "2023-01-01T10:00:00Z"
}
```

## Evaluation Criteria
- [ ] Promise creation and chaining work correctly
- [ ] Promise resolution and rejection function
- [ ] Promise composition and combination operate
- [ ] Async function definition works
- [ ] Await expression handling functions
- [ ] Concurrent task execution successful
- [ ] Parallel processing capabilities work
- [ ] Task scheduling and prioritization operate
- [ ] Task cancellation and timeout function
- [ ] Event loop implementation works
- [ ] Reactive programming patterns function
- [ ] Stream processing operates
- [ ] Code is well-organized and documented
- [ ] Tests cover asynchronous programming functionality

## Resources
- [Asynchronous Programming](https://en.wikipedia.org/wiki/Asynchronous_programming)
- [Promises/A+ Specification](https://promisesaplus.com/)
- [ReactPHP](https://reactphp.org/)
- [Amp](https://amphp.org/)
- [Swoole](https://www.swoole.co.uk/)