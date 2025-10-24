# Challenge 70: Circuit Breaker

## Description
In this challenge, you'll implement a circuit breaker pattern to prevent cascading failures in distributed systems. The circuit breaker monitors service calls and temporarily stops making requests to failing services, allowing them time to recover.

## Learning Objectives
- Understand the circuit breaker pattern and its benefits
- Implement circuit breaker states (Closed, Open, Half-Open)
- Handle failure detection and counting
- Manage timeout and retry mechanisms
- Implement fallback strategies
- Monitor circuit breaker metrics

## Requirements
Create a circuit breaker system with the following features:

1. **Circuit States**:
   - Closed state (normal operation)
   - Open state (circuit tripped, requests blocked)
   - Half-Open state (testing service recovery)
   - State transitions based on failure thresholds
   - Configurable timeout periods

2. **Failure Management**:
   - Failure counting and tracking
   - Exception type filtering
   - Response time monitoring
   - Failure rate calculation
   - Automatic reset mechanisms

3. **Fallback Strategies**:
   - Default response values
   - Alternative service calls
   - Cache-based responses
   - Error response generation
   - Custom fallback handlers

4. **Monitoring and Metrics**:
   - Circuit state tracking
   - Success/failure counters
   - Response time statistics
   - Trip reason logging
   - Alerting integration

## Features to Implement
- [ ] Circuit breaker with three states (Closed, Open, Half-Open)
- [ ] Failure detection and counting
- [ ] Configurable failure thresholds
- [ ] Timeout and retry mechanisms
- [ ] Fallback strategy implementation
- [ ] State transition logic
- [ ] Metrics collection and monitoring
- [ ] Exception filtering
- [ ] Automatic reset functionality
- [ ] Alerting integration

## Project Structure
```
backend-php/
├── src/
│   ├── CircuitBreaker/
│   │   ├── CircuitBreaker.php
│   │   ├── CircuitState.php
│   │   ├── States/
│   │   │   ├── ClosedState.php
│   │   │   ├── OpenState.php
│   │   │   └── HalfOpenState.php
│   │   ├── Exceptions/
│   │   │   ├── CircuitOpenException.php
│   │   │   └── ServiceFailureException.php
│   │   ├── Fallback/
│   │   │   ├── FallbackHandler.php
│   │   │   └── CacheFallback.php
│   │   └── Metrics/
│   │       ├── MetricsCollector.php
│   │       └── MetricsReporter.php
│   ├── Http/
│   │   ├── Request.php
│   │   ├── Response.php
│   │   └── HttpClient.php
│   └── Services/
│       ├── ExternalApiService.php
│       └── PaymentService.php
├── public/
│   └── index.php
├── storage/
│   └── circuit-breaker.json
├── config/
│   └── circuit-breaker.php
└── vendor/

frontend-react/
├── src/
│   ├── api/
│   │   └── circuit-breaker.js
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

## Circuit Breaker Configuration
```php
[
    'failure_threshold' => 5,           // Number of failures before tripping
    'timeout' => 60000,                 // Timeout in milliseconds (1 minute)
    'retry_timeout' => 30000,           // Retry timeout in milliseconds (30 seconds)
    'expected_exceptions' => [          // Exceptions that count as failures
        'ConnectionException',
        'TimeoutException',
        'ServiceUnavailableException'
    ],
    'ignore_exceptions' => [            // Exceptions that don't count as failures
        'ValidationException',
        'NotFoundException'
    ]
]
```

## Circuit Breaker States

### Closed State
- Normal operation
- Requests are allowed
- Failure counter tracks errors
- Trips to Open state when threshold reached

### Open State
- Circuit is tripped
- Requests are blocked immediately
- Fallback responses are returned
- Transitions to Half-Open after timeout

### Half-Open State
- Testing service recovery
- Limited number of requests allowed
- Success resets to Closed state
- Failure returns to Open state

## API Endpoints
```
# Circuit Breaker Management
GET    /circuit-breaker/status
POST   /circuit-breaker/reset
GET    /circuit-breaker/metrics
POST   /circuit-breaker/configure

# Protected Services (with circuit breaker)
GET    /api/external/users
POST   /api/external/payments
GET    /api/external/products
```

## Example Usage
```php
$circuitBreaker = new CircuitBreaker([
    'failure_threshold' => 3,
    'timeout' => 60000,
    'retry_timeout' => 30000
]);

try {
    $result = $circuitBreaker->call(function() {
        return $externalService->getUserData($userId);
    }, function() {
        // Fallback implementation
        return $cache->get("user:$userId");
    });
} catch (CircuitOpenException $e) {
    // Handle circuit breaker open state
    return response()->json(['error' => 'Service temporarily unavailable'], 503);
}
```

## Evaluation Criteria
- [ ] Circuit breaker correctly transitions between states
- [ ] Failure detection and counting work properly
- [ ] Timeout mechanisms function as expected
- [ ] Fallback strategies are implemented
- [ ] Metrics collection is accurate
- [ ] Exception filtering works
- [ ] Automatic reset functionality operates
- [ ] Alerting integration is functional
- [ ] Code is well-organized and documented
- [ ] Tests cover circuit breaker functionality

## Resources
- [Circuit Breaker Pattern](https://martinfowler.com/bliki/CircuitBreaker.html)
- [Netflix Hystrix](https://github.com/Netflix/Hystrix)
- [Microsoft Circuit Breaker](https://docs.microsoft.com/en-us/azure/architecture/patterns/circuit-breaker)
- [Resilience4j](https://resilience4j.readme.io/)