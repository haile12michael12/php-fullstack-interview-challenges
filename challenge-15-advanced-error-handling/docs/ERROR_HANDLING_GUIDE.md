# Error Handling Guide

This guide provides comprehensive information on how error handling is implemented in this application, covering both backend and frontend components.

## Backend Error Handling

### Exception Hierarchy

The application uses a custom exception hierarchy to categorize different types of errors:

```
ApplicationException
├── ValidationException
├── DatabaseException
├── AuthenticationException
├── AuthorizationException
└── ExternalServiceException
```

Each exception type has specific properties and handling logic.

### Global Error Middleware

The [GlobalErrorMiddleware](../backend-php/app/Handler/GlobalErrorMiddleware.php) catches all unhandled exceptions and ensures consistent error responses:

```php
// In middleware
try {
    $response = $next($request, $response);
} catch (ApplicationException $e) {
    // Handle application-specific exceptions
    return $exceptionHandler->handle($e, $request, $response);
} catch (Exception $e) {
    // Handle unexpected exceptions
    return $exceptionHandler->handleUnexpected($e, $request, $response);
}
```

### Exception Handler

The [ExceptionHandler](../backend-php/app/Handler/ExceptionHandler.php) processes exceptions based on their type:

```php
public function handle(ApplicationException $exception, Request $request, Response $response) {
    // Log the exception
    $this->logger->error($exception->getMessage(), [
        'exception' => $exception,
        'correlation_id' => $this->getCorrelationId($request),
    ]);
    
    // Generate appropriate response
    switch (get_class($exception)) {
        case ValidationException::class:
            return $this->handleValidationException($exception, $response);
        case DatabaseException::class:
            return $this->handleDatabaseException($exception, $response);
        // ... other cases
    }
}
```

### Error Response Format

All error responses follow a consistent format:

```json
{
  "error": {
    "type": "VALIDATION_ERROR",
    "message": "Validation failed",
    "code": 422,
    "details": [
      {
        "field": "email",
        "message": "Invalid email format"
      }
    ],
    "correlation_id": "abc-123-def-456",
    "timestamp": "2023-01-01T12:00:00Z"
  }
}
```

## Frontend Error Handling

### Error Boundaries

React [ErrorBoundary](../frontend-react/src/components/ErrorBoundary.jsx) components catch JavaScript errors in the component tree:

```jsx
class ErrorBoundary extends Component {
  constructor(props) {
    super(props);
    this.state = { hasError: false, error: null, errorInfo: null };
  }

  static getDerivedStateFromError(error) {
    return { hasError: true };
  }

  componentDidCatch(error, errorInfo) {
    // Log the error
    errorService.handleGlobalError(error, errorInfo);
    
    this.setState({
      error: error,
      errorInfo: errorInfo
    });
  }

  render() {
    if (this.state.hasError) {
      return <ErrorPage error={this.state.error} errorInfo={this.state.errorInfo} />;
    }

    return this.props.children;
  }
}
```

### API Error Handling

The [apiClient](../frontend-react/src/services/apiClient.js) handles HTTP errors:

```javascript
async request(endpoint, options = {}) {
  try {
    const response = await fetch(url, config);
    
    if (!response.ok) {
      const errorData = await response.json().catch(() => ({}));
      throw new ApiError(
        errorData.message || `HTTP ${response.status}: ${response.statusText}`,
        response.status,
        errorData
      );
    }
    
    return await response.json();
  } catch (error) {
    // Handle network errors
    if (error instanceof ApiError) {
      throw error;
    }
    
    throw new ApiError(
      'Network error: Unable to connect to the server',
      0,
      { originalError: error.message }
    );
  }
}
```

### Error Service

The [errorService](../frontend-react/src/services/errorService.js) centralizes error processing:

```javascript
handleApiError(error, context = {}) {
  if (!(error instanceof ApiError)) {
    logger.error('Non-API error occurred', { error, context });
    return;
  }

  const errorInfo = {
    message: error.message,
    status: error.status,
    correlationId: error.correlationId,
    data: error.data,
    context,
    timestamp: new Date().toISOString(),
  };

  // Log the error
  logger.error('API Error', errorInfo);

  // Notify listeners
  this.notifyErrorListeners(errorInfo);

  // Handle specific error types
  switch (error.status) {
    case 401:
      this.handleUnauthorizedError(errorInfo);
      break;
    case 403:
      this.handleForbiddenError(errorInfo);
      break;
    // ... other cases
  }

  return errorInfo;
}
```

## Recovery Mechanisms

### Retry Mechanism

The [RetryMechanism](../backend-php/app/Recovery/RetryMechanism.php) implements exponential backoff:

```php
public function execute(callable $operation, int $maxRetries = 3) {
    $delay = $this->baseDelay;
    
    for ($i = 0; $i <= $maxRetries; $i++) {
        try {
            return $operation();
        } catch (ExternalServiceException $e) {
            if ($i === $maxRetries) {
                throw $e;
            }
            
            // Wait before retrying
            usleep($delay * 1000);
            $delay *= $this->multiplier;
        }
    }
}
```

### Circuit Breaker

The [CircuitBreaker](../backend-php/app/Recovery/CircuitBreaker.php) prevents cascading failures:

```php
public function call(callable $operation) {
    if ($this->state === self::STATE_OPEN) {
        if ($this->lastFailureTime + $this->timeout < time()) {
            $this->state = self::STATE_HALF_OPEN;
        } else {
            throw new ServiceUnavailableException('Service temporarily unavailable');
        }
    }
    
    try {
        $result = $operation();
        $this->onSuccess();
        return $result;
    } catch (Exception $e) {
        $this->onFailure();
        throw $e;
    }
}
```

### Fallback Strategy

The [FallbackStrategy](../backend-php/app/Recovery/FallbackStrategy.php) provides graceful degradation:

```php
public function executeWithFallback(callable $primary, callable $fallback, array $conditions = []) {
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
```

## Logging

### Structured Logging

All logs include structured context data:

```php
$this->logger->error('Database connection failed', [
    'host' => $config['host'],
    'database' => $config['database'],
    'correlation_id' => $correlationId,
    'attempt' => $attempt,
    'error_code' => $exception->getCode(),
]);
```

### Correlation ID Tracking

Correlation IDs are propagated across services to enable request tracing:

```php
// In middleware
$correlationId = $request->getHeaderLine('X-Correlation-ID') 
    ?: uniqid('req_', true);

// Add to response headers
$response = $response->withHeader('X-Correlation-ID', $correlationId);

// Add to log context
$this->logger->pushProcessor(new CorrelationIdProcessor($correlationId));
```

## Best Practices

### Backend Best Practices

1. **Always throw specific exceptions** - Use custom exception types rather than generic ones
2. **Don't expose internal details** - Sanitize error messages sent to clients
3. **Log with context** - Include relevant information for debugging
4. **Implement recovery mechanisms** - Use retry, circuit breaker, and fallback patterns
5. **Monitor error rates** - Track and alert on error patterns

### Frontend Best Practices

1. **Use error boundaries** - Catch and handle component rendering errors
2. **Provide user feedback** - Show meaningful error messages to users
3. **Implement retry logic** - Automatically retry failed operations when appropriate
4. **Log client-side errors** - Send error information to backend for analysis
5. **Graceful degradation** - Provide fallback experiences when features fail

## Testing Error Handling

### Backend Testing

Test exception handling with unit tests:

```php
public function testDatabaseExceptionHandling() {
    $exception = new DatabaseException('Connection failed');
    $handler = new ExceptionHandler($this->logger);
    
    $response = $handler->handle($exception, $this->request, $this->response);
    
    $this->assertEquals(500, $response->getStatusCode());
    // ... additional assertions
}
```

### Frontend Testing

Test error handling in components:

```javascript
it('should display error message when API call fails', async () => {
  // Mock failed API call
  apiClient.request.mockRejectedValue(new ApiError('Failed', 500));
  
  render(<ErrorDemoPage />);
  
  // Trigger error
  fireEvent.click(screen.getByText('Trigger API Error'));
  
  // Check that error is displayed
  expect(await screen.findByText('Failed')).toBeInTheDocument();
});
```

## Monitoring and Observability

### Error Metrics

Track key error metrics:
- Error rate by endpoint
- Error rate by type
- Recovery success rate
- Average retry attempts

### Distributed Tracing

Use correlation IDs to trace requests across services:
- Propagate correlation IDs in HTTP headers
- Include correlation IDs in all log messages
- Use tracing tools like Jaeger or Zipkin

## Security Considerations

### Information Disclosure

Prevent sensitive information from being exposed in error messages:

```php
// Don't do this - exposes internal details
throw new Exception("Database password: {$password} is invalid");

// Do this instead - generic message
throw new AuthenticationException("Invalid credentials");
```

### Rate Limiting

Implement rate limiting on error endpoints to prevent abuse:

```php
// In middleware
if ($this->rateLimiter->isRateLimited($request)) {
    throw new TooManyRequestsException('Too many requests');
}
```

## Troubleshooting

### Common Issues

1. **Missing exception handling** - Ensure all code paths have appropriate error handling
2. **Inconsistent error responses** - Use centralized exception handlers
3. **Poor error messages** - Provide actionable information to users
4. **Lack of context in logs** - Include relevant context data in log messages
5. **No recovery mechanisms** - Implement retry, circuit breaker, and fallback patterns

### Debugging Tips

1. **Check correlation IDs** - Use correlation IDs to trace request flow
2. **Review log context** - Look for relevant context data in log messages
3. **Test error scenarios** - Verify error handling with unit and integration tests
4. **Monitor error rates** - Watch for increases in error rates that may indicate issues