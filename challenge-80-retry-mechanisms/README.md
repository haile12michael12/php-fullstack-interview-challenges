# Challenge 80: Retry Mechanisms

## Description
In this challenge, you'll implement comprehensive retry mechanisms to handle transient failures in distributed systems. Retry mechanisms are essential for building resilient applications that can recover from temporary issues like network glitches, service unavailability, or database connection problems.

## Learning Objectives
- Understand retry patterns and strategies
- Implement various retry algorithms
- Handle different types of failures
- Create retry policies and configurations
- Manage retry state and context
- Implement circuit breaker integration

## Requirements
Create a retry mechanism system with the following features:

1. **Retry Strategies**:
   - Fixed interval retry
   - Exponential backoff
   - Fibonacci backoff
   - Randomized backoff
   - Linear backoff
   - Custom retry strategies

2. **Failure Handling**:
   - Transient vs. permanent failure detection
   - Exception classification
   - Failure context preservation
   - Retry limit enforcement
   - Graceful degradation

3. **Policy Management**:
   - Retry policy configuration
   - Per-operation retry settings
   - Global retry defaults
   - Policy inheritance
   - Dynamic policy updates

4. **Advanced Features**:
   - Retry state tracking
   - Retry metrics and monitoring
   - Distributed retry coordination
   - Retry context propagation
   - Circuit breaker integration
   - Dead letter queue integration

## Features to Implement
- [ ] Fixed interval retry
- [ ] Exponential backoff
- [ ] Fibonacci backoff
- [ ] Randomized backoff
- [ ] Failure classification
- [ ] Retry policy management
- [ ] Retry state tracking
- [ ] Metrics and monitoring
- [ ] Circuit breaker integration
- [ ] Context propagation
- [ ] Graceful degradation

## Project Structure
```
backend-php/
├── src/
│   ├── Retry/
│   │   ├── RetryMechanism.php
│   │   ├── Strategies/
│   │   │   ├── FixedInterval.php
│   │   │   ├── ExponentialBackoff.php
│   │   │   ├── FibonacciBackoff.php
│   │   │   ├── RandomizedBackoff.php
│   │   │   ├── LinearBackoff.php
│   │   │   └── CustomStrategy.php
│   │   ├── Policies/
│   │   │   ├── RetryPolicy.php
│   │   │   ├── PolicyManager.php
│   │   │   └── PolicyBuilder.php
│   │   ├── Failures/
│   │   │   ├── FailureClassifier.php
│   │   │   ├── TransientFailure.php
│   │   │   ├── PermanentFailure.php
│   │   │   └── FailureContext.php
│   │   ├── State/
│   │   │   ├── RetryState.php
│   │   │   ├── AttemptTracker.php
│   │   │   └── ContextManager.php
│   │   └── Integration/
│   │       ├── CircuitBreakerIntegration.php
│   │       ├── MetricsCollector.php
│   │       └── DeadLetterQueue.php
│   ├── Http/
│   │   ├── Request.php
│   │   ├── Response.php
│   │   └── HttpClient.php
│   └── Services/
├── public/
│   └── index.php
├── storage/
│   └── retry/
├── config/
│   └── retry.php
└── vendor/

frontend-react/
├── src/
│   ├── api/
│   │   └── retry.js
│   ├── components/
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

## Retry Strategies

### Fixed Interval Retry
Retry at fixed intervals:
```php
class FixedInterval implements RetryStrategy
{
    private $interval;
    
    public function __construct($interval = 1000)
    {
        $this->interval = $interval; // milliseconds
    }
    
    public function getNextDelay($attempt)
    {
        return $this->interval;
    }
}
```

### Exponential Backoff
Double the delay with each attempt:
```php
class ExponentialBackoff implements RetryStrategy
{
    private $baseDelay;
    private $maxDelay;
    private $multiplier;
    
    public function __construct($baseDelay = 1000, $maxDelay = 60000, $multiplier = 2)
    {
        $this->baseDelay = $baseDelay;
        $this->maxDelay = $maxDelay;
        $this->multiplier = $multiplier;
    }
    
    public function getNextDelay($attempt)
    {
        $delay = $this->baseDelay * pow($this->multiplier, $attempt - 1);
        return min($delay, $this->maxDelay);
    }
}
```

### Fibonacci Backoff
Use Fibonacci sequence for delays:
```php
class FibonacciBackoff implements RetryStrategy
{
    private $baseDelay;
    private $maxDelay;
    
    public function __construct($baseDelay = 1000, $maxDelay = 60000)
    {
        $this->baseDelay = $baseDelay;
        $this->maxDelay = $maxDelay;
    }
    
    public function getNextDelay($attempt)
    {
        $fib = $this->fibonacci($attempt);
        $delay = $this->baseDelay * $fib;
        return min($delay, $this->maxDelay);
    }
    
    private function fibonacci($n)
    {
        if ($n <= 1) return $n;
        return $this->fibonacci($n - 1) + $this->fibonacci($n - 2);
    }
}
```

### Randomized Backoff
Add jitter to prevent thundering herd:
```php
class RandomizedBackoff implements RetryStrategy
{
    private $baseStrategy;
    private $jitter;
    
    public function __construct(RetryStrategy $baseStrategy, $jitter = 0.2)
    {
        $this->baseStrategy = $baseStrategy;
        $this->jitter = $jitter;
    }
    
    public function getNextDelay($attempt)
    {
        $baseDelay = $this->baseStrategy->getNextDelay($attempt);
        $jitterAmount = $baseDelay * $this->jitter;
        $randomJitter = mt_rand(-$jitterAmount, $jitterAmount);
        return max(0, $baseDelay + $randomJitter);
    }
}
```

## Retry Mechanism Implementation
```php
class RetryMechanism
{
    private $policy;
    private $strategy;
    private $failureClassifier;
    
    public function __construct(RetryPolicy $policy, RetryStrategy $strategy)
    {
        $this->policy = $policy;
        $this->strategy = $strategy;
        $this->failureClassifier = new FailureClassifier();
    }
    
    public function execute(callable $operation, $context = [])
    {
        $state = new RetryState($context);
        
        while (true) {
            try {
                $result = $operation();
                $this->onSuccess($state);
                return $result;
            } catch (Exception $e) {
                if (!$this->shouldRetry($e, $state)) {
                    throw $e;
                }
                
                $this->onFailure($e, $state);
                $delay = $this->strategy->getNextDelay($state->getAttempt());
                $this->wait($delay);
            }
        }
    }
    
    private function shouldRetry(Exception $exception, RetryState $state)
    {
        // Check if we've exceeded max attempts
        if ($state->getAttempt() >= $this->policy->getMaxAttempts()) {
            return false;
        }
        
        // Check if this is a transient failure
        if (!$this->failureClassifier->isTransient($exception)) {
            return false;
        }
        
        // Check custom retry conditions
        if ($this->policy->hasCustomCondition() && 
            !$this->policy->evaluateCondition($exception, $state)) {
            return false;
        }
        
        return true;
    }
    
    private function wait($milliseconds)
    {
        usleep($milliseconds * 1000);
    }
}
```

## Failure Classification
```php
class FailureClassifier
{
    private $transientExceptions = [
        'ConnectionException',
        'TimeoutException',
        'ServiceUnavailableException',
        'NetworkException'
    ];
    
    private $permanentExceptions = [
        'ValidationException',
        'NotFoundException',
        'UnauthorizedException',
        'ForbiddenException'
    ];
    
    public function isTransient(Exception $exception)
    {
        $exceptionClass = get_class($exception);
        
        // Check explicit classifications
        if (in_array($exceptionClass, $this->permanentExceptions)) {
            return false;
        }
        
        if (in_array($exceptionClass, $this->transientExceptions)) {
            return true;
        }
        
        // Check by message patterns
        $message = $exception->getMessage();
        if (preg_match('/timeout|connection|network|temporary/i', $message)) {
            return true;
        }
        
        // Default to transient for unknown exceptions
        return true;
    }
    
    public function classify(Exception $exception)
    {
        if ($this->isTransient($exception)) {
            return new TransientFailure($exception);
        }
        
        return new PermanentFailure($exception);
    }
}
```

## Retry Policy Configuration
```php
[
    'defaults' => [
        'max_attempts' => 3,
        'strategy' => 'exponential_backoff',
        'base_delay' => 1000,
        'max_delay' => 60000,
        'jitter' => 0.2
    ],
    'policies' => [
        'database' => [
            'max_attempts' => 5,
            'strategy' => 'fixed_interval',
            'interval' => 2000
        ],
        'external_api' => [
            'max_attempts' => 3,
            'strategy' => 'exponential_backoff_with_jitter',
            'base_delay' => 1000,
            'max_delay' => 30000
        ],
        'critical_operation' => [
            'max_attempts' => 10,
            'strategy' => 'fibonacci_backoff',
            'base_delay' => 500,
            'max_delay' => 120000
        ]
    ],
    'exceptions' => [
        'transient' => [
            'ConnectionException',
            'TimeoutException',
            'ServiceUnavailableException'
        ],
        'permanent' => [
            'ValidationException',
            'NotFoundException',
            'UnauthorizedException'
        ]
    ],
    'integration' => [
        'circuit_breaker' => true,
        'metrics' => true,
        'dead_letter_queue' => true
    ]
]
```

## Retry Policy Builder
```php
class PolicyBuilder
{
    private $policy;
    
    public function __construct()
    {
        $this->policy = new RetryPolicy();
    }
    
    public function maxAttempts($attempts)
    {
        $this->policy->setMaxAttempts($attempts);
        return $this;
    }
    
    public function exponentialBackoff($baseDelay = 1000, $maxDelay = 60000)
    {
        $strategy = new ExponentialBackoff($baseDelay, $maxDelay);
        $this->policy->setStrategy($strategy);
        return $this;
    }
    
    public function fixedInterval($interval)
    {
        $strategy = new FixedInterval($interval);
        $this->policy->setStrategy($strategy);
        return $this;
    }
    
    public function withJitter($jitter = 0.2)
    {
        $currentStrategy = $this->policy->getStrategy();
        $strategy = new RandomizedBackoff($currentStrategy, $jitter);
        $this->policy->setStrategy($strategy);
        return $this;
    }
    
    public function onlyIf(callable $condition)
    {
        $this->policy->setCustomCondition($condition);
        return $this;
    }
    
    public function build()
    {
        return $this->policy;
    }
}

// Usage example:
$policy = (new PolicyBuilder())
    ->maxAttempts(5)
    ->exponentialBackoff(1000, 30000)
    ->withJitter(0.1)
    ->onlyIf(function($exception, $state) {
        return $state->getAttempt() < 3;
    })
    ->build();
```

## API Endpoints
```
# Retry Mechanism Management
GET    /retry/policies           # List all retry policies
GET    /retry/policies/{name}   # Get specific policy
POST   /retry/policies           # Create new policy
PUT    /retry/policies/{name}   # Update policy
DELETE /retry/policies/{name}   # Delete policy
GET    /retry/strategies         # List available strategies
GET    /retry/stats              # Get retry statistics
GET    /retry/failures           # List recent failures
POST   /retry/failures/retry     # Retry specific failure
GET    /retry/metrics            # Get detailed metrics
```

## Retry Statistics Response
```json
{
  "overall": {
    "total_operations": 15420,
    "successful": 14250,
    "failed": 1170,
    "retry_attempts": 3420,
    "success_rate": 0.924
  },
  "by_policy": {
    "database": {
      "total": 4250,
      "successful": 4120,
      "failed": 130,
      "retry_attempts": 842,
      "success_rate": 0.969
    },
    "external_api": {
      "total": 6780,
      "successful": 6250,
      "failed": 530,
      "retry_attempts": 1842,
      "success_rate": 0.922
    },
    "critical_operation": {
      "total": 4390,
      "successful": 3880,
      "failed": 510,
      "retry_attempts": 736,
      "success_rate": 0.884
    }
  },
  "performance": {
    "average_retries_per_failure": 2.9,
    "average_retry_delay": "2.4s",
    "max_retry_delay": "30s",
    "total_retry_time": "2h 15m"
  }
}
```

## Failure Details Response
```json
{
  "failures": [
    {
      "id": "5f4d4a4b4c4d4e4f50515253",
      "operation": "database.query",
      "exception": "ConnectionException",
      "message": "Connection timed out after 30 seconds",
      "attempts": 5,
      "policy": "database",
      "context": {
        "query": "SELECT * FROM users WHERE active = 1",
        "host": "db-primary.internal"
      },
      "first_attempt": "2023-01-01T10:00:00Z",
      "last_attempt": "2023-01-01T10:00:45Z",
      "status": "failed"
    }
  ]
}
```

## Evaluation Criteria
- [ ] Fixed interval retry works correctly
- [ ] Exponential backoff increases delays
- [ ] Fibonacci backoff uses sequence
- [ ] Randomized backoff adds jitter
- [ ] Failure classification distinguishes types
- [ ] Retry policy management functions
- [ ] Retry state tracking maintains context
- [ ] Metrics and monitoring collect data
- [ ] Circuit breaker integration works
- [ ] Context propagation preserves state
- [ ] Graceful degradation handles failures
- [ ] Code is well-organized and documented
- [ ] Tests cover retry functionality

## Resources
- [Retry Pattern](https://docs.microsoft.com/en-us/azure/architecture/patterns/retry)
- [Exponential Backoff](https://en.wikipedia.org/wiki/Exponential_backoff)
- [Circuit Breaker Pattern](https://martinfowler.com/bliki/CircuitBreaker.html)
- [Fault Tolerance Patterns](https://netflix.github.io/falcor/documentation/fault-tolerance.html)