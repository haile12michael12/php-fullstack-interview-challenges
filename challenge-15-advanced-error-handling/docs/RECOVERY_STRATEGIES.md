# Recovery Strategies Guide

This guide explains the recovery mechanisms implemented in the application to ensure resilience and fault tolerance.

## Overview

The application implements three primary recovery strategies:

1. **Retry Mechanism** - Automatically retry failed operations
2. **Circuit Breaker** - Prevent cascading failures
3. **Fallback Strategy** - Provide graceful degradation

These patterns work together to create a resilient system that can handle various failure scenarios.

## Retry Mechanism

### Implementation

The [RetryMechanism](../backend-php/app/Recovery/RetryMechanism.php) implements exponential backoff with jitter:

```php
class RetryMechanism {
    private int $maxRetries;
    private int $baseDelay;
    private int $maxDelay;
    private float $multiplier;
    
    public function execute(callable $operation, callable $shouldRetry = null) {
        $delay = $this->baseDelay;
        
        for ($i = 0; $i <= $this->maxRetries; $i++) {
            try {
                return $operation();
            } catch (Exception $e) {
                // Check if we should retry this exception
                if (!$this->shouldRetry($e, $shouldRetry) || $i === $this->maxRetries) {
                    throw $e;
                }
                
                // Wait before retrying
                usleep($this->calculateDelay($delay, $i) * 1000);
            }
        }
    }
    
    private function calculateDelay(int $delay, int $attempt): int {
        // Exponential backoff with jitter
        $backoffDelay = min($delay * pow($this->multiplier, $attempt), $this->maxDelay);
        $jitter = mt_rand(0, (int)($backoffDelay * 0.1));
        return (int)($backoffDelay + $jitter);
    }
}
```

### Configuration

Retry mechanisms are configured in [config/recovery.php](../backend-php/config/recovery.php):

```php
return [
    'retry' => [
        'default' => [
            'max_retries' => 3,
            'base_delay' => 1000,  // 1 second
            'max_delay' => 30000,  // 30 seconds
            'multiplier' => 2.0,
        ],
        'database' => [
            'max_retries' => 5,
            'base_delay' => 500,
            'max_delay' => 10000,
            'multiplier' => 1.5,
        ],
        'external_api' => [
            'max_retries' => 3,
            'base_delay' => 2000,
            'max_delay' => 60000,
            'multiplier' => 2.0,
        ],
    ],
];
```

### Usage Examples

#### Database Operations

```php
class DatabaseService {
    private RetryMechanism $retryMechanism;
    
    public function query(string $sql, array $params = []) {
        return $this->retryMechanism->execute(
            function() use ($sql, $params) {
                return $this->connection->executeQuery($sql, $params);
            },
            function(Exception $e) {
                // Retry on transient database errors
                return $e instanceof DatabaseException && 
                       in_array($e->getCode(), [1205, 1213, 2002, 2006]);
            }
        );
    }
}
```

#### External API Calls

```php
class ExternalApiService {
    private RetryMechanism $retryMechanism;
    
    public function getUserData(string $userId) {
        return $this->retryMechanism->execute(
            function() use ($userId) {
                $response = $this->httpClient->get("/users/{$userId}");
                return json_decode($response->getBody(), true);
            },
            function(Exception $e) {
                // Retry on network errors or 5xx responses
                return $e instanceof ExternalServiceException && 
                       ($e->getCode() === 0 || $e->getCode() >= 500);
            }
        );
    }
}
```

### Retry Conditions

Different operations have different retry conditions:

1. **Database Operations** - Retry on deadlock, lock wait timeout, connection errors
2. **External APIs** - Retry on network errors, 5xx responses, rate limiting
3. **File Operations** - Retry on temporary file system errors
4. **Cache Operations** - Retry on temporary cache server issues

## Circuit Breaker

### Implementation

The [CircuitBreaker](../backend-php/app/Recovery/CircuitBreaker.php) prevents cascading failures:

```php
class CircuitBreaker {
    private const STATE_CLOSED = 'closed';
    private const STATE_OPEN = 'open';
    private const STATE_HALF_OPEN = 'half_open';
    
    private string $state = self::STATE_CLOSED;
    private int $failureCount = 0;
    private int $successCount = 0;
    private ?int $lastFailureTime = null;
    
    public function call(callable $operation) {
        $this->guard();
        
        try {
            $result = $operation();
            $this->onSuccess();
            return $result;
        } catch (Exception $e) {
            $this->onFailure();
            throw $e;
        }
    }
    
    private function guard(): void {
        if ($this->state === self::STATE_OPEN) {
            if ($this->lastFailureTime + $this->timeout < time()) {
                $this->state = self::STATE_HALF_OPEN;
                $this->successCount = 0;
            } else {
                throw new ServiceUnavailableException('Service temporarily unavailable');
            }
        }
    }
    
    private function onSuccess(): void {
        if ($this->state === self::STATE_HALF_OPEN) {
            $this->successCount++;
            if ($this->successCount >= $this->successThreshold) {
                $this->reset();
            }
        } else {
            $this->failureCount = 0;
        }
    }
    
    private function onFailure(): void {
        $this->failureCount++;
        $this->lastFailureTime = time();
        
        if ($this->failureCount >= $this->failureThreshold) {
            $this->state = self::STATE_OPEN;
        }
    }
}
```

### Configuration

Circuit breakers are configured in [config/recovery.php](../backend-php/config/recovery.php):

```php
return [
    'circuit_breaker' => [
        'default' => [
            'failure_threshold' => 5,
            'success_threshold' => 2,
            'timeout' => 60,  // 1 minute
        ],
        'external_api' => [
            'failure_threshold' => 3,
            'success_threshold' => 1,
            'timeout' => 30,  // 30 seconds
        ],
        'database' => [
            'failure_threshold' => 10,
            'success_threshold' => 3,
            'timeout' => 120, // 2 minutes
        ],
    ],
];
```

### Usage Examples

#### External Service Protection

```php
class ExternalApiService {
    private CircuitBreaker $circuitBreaker;
    
    public function getUserData(string $userId) {
        return $this->circuitBreaker->call(function() use ($userId) {
            $response = $this->httpClient->get("/users/{$userId}");
            return json_decode($response->getBody(), true);
        });
    }
}
```

#### Database Connection Protection

```php
class DatabaseService {
    private CircuitBreaker $circuitBreaker;
    
    public function executeQuery(string $sql, array $params = []) {
        return $this->circuitBreaker->call(function() use ($sql, $params) {
            return $this->connection->executeQuery($sql, $params);
        });
    }
}
```

### States

1. **Closed** - Normal operation, failures are counted
2. **Open** - Requests fail immediately, timeout period active
3. **Half-Open** - Limited requests allowed to test service availability

## Fallback Strategy

### Implementation

The [FallbackStrategy](../backend-php/app/Recovery/FallbackStrategy.php) provides graceful degradation:

```php
class FallbackStrategy {
    public function executeWithFallback(
        callable $primary, 
        callable $fallback, 
        array $conditions = []
    ) {
        try {
            $result = $primary();
            
            // Check if result meets conditions for fallback
            if ($this->shouldFallback($result, $conditions)) {
                return $fallback();
            }
            
            return $result;
        } catch (Exception $e) {
            if ($this->shouldFallbackException($e, $conditions)) {
                return $fallback();
            }
            
            throw $e;
        }
    }
    
    private function shouldFallback($result, array $conditions): bool {
        if (isset($conditions['null_result']) && $result === null) {
            return true;
        }
        
        if (isset($conditions['empty_result']) && empty($result)) {
            return true;
        }
        
        if (isset($conditions['validation_callback'])) {
            return call_user_func($conditions['validation_callback'], $result);
        }
        
        return false;
    }
}
```

### Configuration

Fallback strategies are configured in [config/recovery.php](../backend-php/config/recovery.php):

```php
return [
    'fallback' => [
        'user_data' => [
            'cache_ttl' => 3600,  // 1 hour
            'default_data' => [
                'name' => 'Anonymous User',
                'avatar' => '/default-avatar.png',
            ],
        ],
        'product_catalog' => [
            'cache_ttl' => 7200,  // 2 hours
            'default_products' => [
                // Default product data
            ],
        ],
    ],
];
```

### Usage Examples

#### User Data Fallback

```php
class UserService {
    private FallbackStrategy $fallbackStrategy;
    
    public function getUserProfile(string $userId) {
        return $this->fallbackStrategy->executeWithFallback(
            // Primary operation
            function() use ($userId) {
                return $this->userRepository->findById($userId);
            },
            // Fallback operation
            function() use ($userId) {
                // Return cached data or default profile
                return $this->getCachedUserProfile($userId) ?: $this->getDefaultProfile();
            },
            // Fallback conditions
            [
                'null_result' => true,
                'validation_callback' => function($result) {
                    return !$this->isValidUserProfile($result);
                }
            ]
        );
    }
}
```

#### Product Catalog Fallback

```php
class ProductService {
    private FallbackStrategy $fallbackStrategy;
    
    public function getProductCatalog() {
        return $this->fallbackStrategy->executeWithFallback(
            // Primary operation
            function() {
                return $this->externalCatalogService->getProducts();
            },
            // Fallback operation
            function() {
                // Return cached catalog or default products
                return $this->getCachedCatalog() ?: $this->getDefaultProducts();
            },
            // Fallback conditions
            [
                'empty_result' => true,
                'validation_callback' => function($result) {
                    return count($result) < 5; // Minimum expected products
                }
            ]
        );
    }
}
```

## Frontend Recovery Strategies

### Retry Handler

The frontend [RetryHandler](../frontend-react/src/services/retryHandler.js) implements similar retry logic:

```javascript
class RetryHandler {
  async execute(fn, shouldRetry = () => true) {
    let lastError;
    
    for (let attempt = 0; attempt <= this.maxRetries; attempt++) {
      try {
        const result = await fn();
        return result;
      } catch (error) {
        lastError = error;
        
        // Check if we should retry this error
        if (!shouldRetry(error) || attempt === this.maxRetries) {
          throw error;
        }
        
        // Wait before retrying
        const delay = this.calculateDelay(attempt);
        await new Promise(resolve => setTimeout(resolve, delay));
      }
    }
    
    throw lastError;
  }
}
```

### Fallback Views

React components provide fallback UIs:

```jsx
// FallbackView.jsx
const FallbackView = ({ type, onRetry }) => {
  switch (type) {
    case 'network_error':
      return (
        <div className="fallback-view">
          <h3>Network Error</h3>
          <p>Unable to connect to the server. Please check your connection.</p>
          <button onClick={onRetry}>Retry</button>
        </div>
      );
    case 'service_unavailable':
      return (
        <div className="fallback-view">
          <h3>Service Unavailable</h3>
          <p>The service is temporarily unavailable. Please try again later.</p>
          <MaintenanceCountdown />
        </div>
      );
    default:
      return (
        <div className="fallback-view">
          <h3>Something Went Wrong</h3>
          <p>We're experiencing technical difficulties. Please try again.</p>
          <button onClick={onRetry}>Retry</button>
        </div>
      );
  }
};
```

## Integration Patterns

### Combined Recovery

Multiple recovery strategies can be combined:

```php
class ResilientService {
    private RetryMechanism $retryMechanism;
    private CircuitBreaker $circuitBreaker;
    private FallbackStrategy $fallbackStrategy;
    
    public function getData(string $id) {
        return $this->fallbackStrategy->executeWithFallback(
            // Primary operation with retry and circuit breaker
            function() use ($id) {
                return $this->circuitBreaker->call(function() use ($id) {
                    return $this->retryMechanism->execute(function() use ($id) {
                        return $this->externalService->fetchData($id);
                    });
                });
            },
            // Fallback operation
            function() use ($id) {
                return $this->getCachedData($id) ?: $this->getDefaultData($id);
            }
        );
    }
}
```

### Health Checks

Recovery mechanisms integrate with health checks:

```php
class HealthController {
    public function checkRecoveryStatus() {
        return [
            'retry_mechanisms' => $this->getRetryStatus(),
            'circuit_breakers' => $this->getCircuitBreakerStatus(),
            'fallback_strategies' => $this->getFallbackStatus(),
        ];
    }
    
    private function getCircuitBreakerStatus(): array {
        return array_map(function(CircuitBreaker $breaker) {
            return [
                'state' => $breaker->getState(),
                'failure_count' => $breaker->getFailureCount(),
                'last_failure' => $breaker->getLastFailureTime(),
            ];
        }, $this->circuitBreakers);
    }
}
```

## Configuration Management

### Environment-Specific Settings

Different environments have different recovery settings:

```php
// config/recovery.php
return [
    'environments' => [
        'development' => [
            'retry' => [
                'max_retries' => 1,
                'base_delay' => 100,
            ],
            'circuit_breaker' => [
                'failure_threshold' => 10,
                'timeout' => 10,
            ],
        ],
        'production' => [
            'retry' => [
                'max_retries' => 3,
                'base_delay' => 1000,
            ],
            'circuit_breaker' => [
                'failure_threshold' => 5,
                'timeout' => 60,
            ],
        ],
    ],
];
```

### Dynamic Configuration

Recovery settings can be adjusted dynamically:

```php
class RecoveryManager {
    public function updateRetrySettings(string $service, array $settings): void {
        $this->retryMechanisms[$service]->updateSettings($settings);
    }
    
    public function resetCircuitBreaker(string $service): void {
        $this->circuitBreakers[$service]->reset();
    }
}
```

## Monitoring and Metrics

### Recovery Metrics

Track key recovery metrics:

```php
class RecoveryMetrics {
    public function recordRetry(string $service, int $attempt, bool $success): void {
        $this->metrics->increment("retries.{$service}", 1, [
            'attempt' => $attempt,
            'success' => $success,
        ]);
    }
    
    public function recordCircuitBreakerChange(string $service, string $from, string $to): void {
        $this->metrics->increment("circuit_breaker.state_changes.{$service}", 1, [
            'from' => $from,
            'to' => $to,
        ]);
    }
    
    public function recordFallback(string $service, string $reason): void {
        $this->metrics->increment("fallbacks.{$service}", 1, [
            'reason' => $reason,
        ]);
    }
}
```

### Dashboard Integration

Expose recovery status for monitoring dashboards:

```php
class RecoveryDashboardController {
    public function getStatus() {
        return [
            'services' => array_map(function($service) {
                return [
                    'name' => $service->getName(),
                    'retry_stats' => $service->getRetryStats(),
                    'circuit_breaker_status' => $service->getCircuitBreaker()->getStatus(),
                    'fallback_usage' => $service->getFallbackUsage(),
                ];
            }, $this->services),
        ];
    }
}
```

## Testing Recovery Strategies

### Unit Tests

Test individual recovery components:

```php
class RetryMechanismTest extends TestCase {
    public function testSuccessfulOperationNoRetry() {
        $mechanism = new RetryMechanism(['max_retries' => 3]);
        $operation = $this->createMockOperation(1); // Succeeds on first try
        
        $result = $mechanism->execute($operation);
        
        $this->assertEquals('success', $result);
        $this->assertEquals(1, $operation->getCallCount());
    }
    
    public function testRetryOnTransientFailure() {
        $mechanism = new RetryMechanism(['max_retries' => 3]);
        $operation = $this->createFlakyOperation([true, true, false]); // Fails twice, succeeds third
        
        $result = $mechanism->execute($operation);
        
        $this->assertEquals('success', $result);
        $this->assertEquals(3, $operation->getCallCount());
    }
}
```

### Integration Tests

Test recovery strategies in realistic scenarios:

```php
class ApiRecoveryTest extends TestCase {
    public function testCircuitBreakerOpensAfterFailures() {
        $service = $this->createUnstableService();
        $circuitBreaker = new CircuitBreaker(['failure_threshold' => 3]);
        
        // Cause 3 consecutive failures
        for ($i = 0; $i < 3; $i++) {
            try {
                $circuitBreaker->call([$service, 'unstableMethod']);
                $this->fail('Expected exception');
            } catch (Exception $e) {
                // Expected
            }
        }
        
        // Next call should fail immediately
        $this->expectException(ServiceUnavailableException::class);
        $circuitBreaker->call([$service, 'unstableMethod']);
    }
}
```

## Best Practices

### Recovery Strategy Selection

Choose appropriate recovery strategies for different scenarios:

1. **Retry** - For transient failures (network glitches, temporary resource unavailability)
2. **Circuit Breaker** - For protecting against cascading failures
3. **Fallback** - For graceful degradation when primary services fail

### Configuration Guidelines

1. **Start conservative** - Use lower thresholds in production
2. **Monitor closely** - Track recovery mechanism effectiveness
3. **Adjust based on data** - Tune settings based on actual failure patterns
4. **Consider service dependencies** - Account for downstream service characteristics

### Implementation Tips

1. **Combine strategies** - Use multiple recovery mechanisms together for better resilience
2. **Fail fast** - Don't wait unnecessarily when services are known to be down
3. **Provide meaningful feedback** - Inform users when fallback data is being used
4. **Log recovery events** - Track when and why recovery mechanisms are triggered
5. **Test failure scenarios** - Regularly test how your application behaves under failure conditions

## Troubleshooting

### Common Issues

1. **Aggressive retries causing overload** - Reduce retry frequency or implement backpressure
2. **Circuit breakers staying open too long** - Adjust timeout settings based on service recovery times
3. **Fallback data becoming stale** - Implement cache expiration and refresh strategies
4. **Recovery mechanisms not triggering** - Verify exception types and conditions match expectations

### Debugging Tips

1. **Enable detailed logging** - Log recovery mechanism decisions and state changes
2. **Monitor metrics** - Watch for patterns in retry counts, circuit breaker states, and fallback usage
3. **Test with chaos engineering** - Introduce controlled failures to validate recovery mechanisms
4. **Review failure patterns** - Analyze logs to understand common failure scenarios and optimize accordingly