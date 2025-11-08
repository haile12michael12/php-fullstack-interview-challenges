# Logging Guide

This guide explains the logging implementation in the application, covering both backend (PHP) and frontend (React) components.

## Backend Logging (PHP)

### Logger Factory

The [LoggerFactory](../backend-php/app/Logger/LoggerFactory.php) creates configured logger instances:

```php
class LoggerFactory {
    public static function create(string $name, array $config = []): LoggerInterface {
        $logger = new Logger($name);
        
        // Add handlers based on configuration
        if ($config['file_logging'] ?? true) {
            $fileHandler = new StreamHandler(
                $config['log_file'] ?? 'storage/logs/app.log',
                $config['log_level'] ?? Logger::DEBUG
            );
            $logger->pushHandler($fileHandler);
        }
        
        // Add processors for context
        $logger->pushProcessor(new CorrelationIdProcessor());
        $logger->pushProcessor(new WebProcessor());
        
        return $logger;
    }
}
```

### Context Logger

The [ContextLogger](../backend-php/app/Logger/ContextLogger.php) adds contextual information to log messages:

```php
class ContextLogger {
    private LoggerInterface $logger;
    private array $context = [];
    
    public function __construct(LoggerInterface $logger) {
        $this->logger = $logger;
    }
    
    public function addContext(array $context): void {
        $this->context = array_merge($this->context, $context);
    }
    
    public function info(string $message, array $context = []): void {
        $this->logger->info($message, array_merge($this->context, $context));
    }
    
    public function error(string $message, array $context = []): void {
        $this->logger->error($message, array_merge($this->context, $context));
    }
    
    // ... other log levels
}
```

### Correlation ID Middleware

The [CorrelationIdMiddleware](../backend-php/app/Logger/CorrelationIdMiddleware.php) ensures consistent request tracking:

```php
class CorrelationIdMiddleware {
    public function __invoke(Request $request, Response $response, callable $next): Response {
        // Get or generate correlation ID
        $correlationId = $request->getHeaderLine('X-Correlation-ID') 
            ?: uniqid('req_', true);
        
        // Add to request attributes
        $request = $request->withAttribute('correlation_id', $correlationId);
        
        // Process request
        $response = $next($request, $response);
        
        // Add to response headers
        return $response->withHeader('X-Correlation-ID', $correlationId);
    }
}
```

### Log Message Structure

All log messages follow a consistent structure:

```
[2023-01-01 12:00:00] app.ERROR: Database connection failed {"host":"localhost","database":"myapp","correlation_id":"req_abc123","attempt":3,"error_code":1045} []
```

Components:
1. Timestamp - ISO 8601 formatted
2. Channel - Logger name
3. Level - Log level (DEBUG, INFO, WARNING, ERROR, CRITICAL)
4. Message - Human-readable message
5. Context - Structured data as JSON
6. Extra - Additional data (usually empty)

### Log Levels

The application uses standard PSR-3 log levels:

- **DEBUG** - Detailed debug information
- **INFO** - Interesting events
- **WARNING** - Exceptional occurrences that are not errors
- **ERROR** - Runtime errors that do not require immediate action
- **CRITICAL** - Critical conditions

Example usage:

```php
// Debug - Detailed information for diagnosing problems
$this->logger->debug('Processing user registration', [
    'user_id' => $userId,
    'step' => 'validation'
]);

// Info - Normal but significant events
$this->logger->info('User registered successfully', [
    'user_id' => $userId,
    'email' => $email
]);

// Warning - Exceptional occurrences
$this->logger->warning('Deprecated API endpoint used', [
    'endpoint' => '/api/v1/users',
    'user_agent' => $userAgent
]);

// Error - Runtime errors
$this->logger->error('Database connection failed', [
    'host' => $host,
    'error' => $exception->getMessage()
]);

// Critical - Critical conditions
$this->logger->critical('Application unable to start', [
    'reason' => 'Database connection failed',
    'host' => $host
]);
```

### Configuration

Logging is configured through [config/logging.php](../backend-php/config/logging.php):

```php
return [
    'default' => 'file',
    'channels' => [
        'file' => [
            'driver' => 'file',
            'path' => 'storage/logs/app.log',
            'level' => 'debug',
            'max_files' => 30,
        ],
        'error' => [
            'driver' => 'file',
            'path' => 'storage/logs/error.log',
            'level' => 'error',
            'max_files' => 30,
        ],
        'syslog' => [
            'driver' => 'syslog',
            'level' => 'info',
        ],
    ],
];
```

## Frontend Logging (React)

### Logger Utility

The frontend [logger](../frontend-react/src/utils/logger.js) provides structured logging:

```javascript
class Logger {
  constructor(options = {}) {
    this.level = options.level || 'info';
    this.correlationId = null;
  }
  
  // Log an error message
  error(message, context = {}) {
    if (!this.shouldLog('ERROR')) return;
    
    const formattedMessage = this.formatMessage('ERROR', message, context);
    console.error(formattedMessage);
    
    // Send to logging service in production
    this.sendToLoggingService('ERROR', message, context);
  }
  
  // Format log message with timestamp, level, and context
  formatMessage(level, message, context = {}) {
    const timestamp = new Date().toISOString();
    const correlationInfo = this.correlationId ? `[${this.correlationId}]` : '';
    
    const baseMessage = `${timestamp} ${correlationInfo}[${level.toUpperCase()}] ${message}`;
    
    if (Object.keys(context).length > 0) {
      return `${baseMessage} | Context: ${JSON.stringify(context)}`;
    }
    
    return baseMessage;
  }
}
```

### Correlation ID Tracking

Frontend logging integrates with backend correlation IDs:

```javascript
// In API client
const correlationId = this.generateCorrelationId();
config.headers['X-Correlation-ID'] = correlationId;

// Set in logger
logger.setCorrelationId(correlationId);

// Log with context
logger.error('API request failed', {
  endpoint: url,
  method: config.method,
  status: response.status,
  correlationId: correlationId
});
```

### Log Message Structure

Frontend log messages follow a similar structure:

```
2023-01-01T12:00:00.000Z [req_abc123][ERROR] API request failed | Context: {"endpoint":"/api/users","method":"GET","status":500}
```

## Contextual Information

Both backend and frontend loggers include contextual information:

### Backend Context

- Correlation ID
- Request URI
- HTTP method
- Client IP
- User agent
- Authenticated user ID
- Session ID
- Request parameters (sanitized)

### Frontend Context

- Correlation ID
- Browser information
- Screen dimensions
- Network status
- User actions
- Component state
- Error stack traces

## Structured Logging

All logs use structured data to enable easy querying and analysis:

```php
// Backend - Monolog with context
$this->logger->error('Payment processing failed', [
    'payment_id' => $paymentId,
    'amount' => $amount,
    'currency' => $currency,
    'provider' => $provider,
    'error_code' => $errorCode,
    'correlation_id' => $correlationId,
    'user_id' => $userId,
]);
```

```javascript
// Frontend - Logger with context
logger.error('Payment form submission failed', {
  paymentId: paymentId,
  amount: amount,
  currency: currency,
  errorCode: errorCode,
  correlationId: correlationId,
  userId: userId,
  formValues: sanitizedFormValues,
});
```

## Log Rotation and Retention

### Backend

Logs are rotated daily and retained for 30 days by default:

```php
// In StreamHandler configuration
$fileHandler = new RotatingFileHandler(
    'storage/logs/app.log',
    30, // Max files to keep
    Logger::DEBUG
);
```

Configuration options in [config/logging.php](../backend-php/config/logging.php):

```php
return [
    'channels' => [
        'file' => [
            'max_files' => 30,     // Number of days to retain logs
            'file_size' => '10MB', // Maximum file size
            'compress' => true,    // Compress rotated logs
        ],
    ],
];
```

### Frontend

Browser console logs are ephemeral, but production logs are sent to backend services:

```javascript
// In production, send logs to backend
sendToLoggingService(level, message, context) {
  if (process.env.NODE_ENV === 'production') {
    fetch('/api/logs', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        level,
        message,
        context,
        timestamp: new Date().toISOString(),
        correlationId: this.correlationId,
        userAgent: navigator.userAgent,
        url: window.location.href,
      }),
    }).catch(() => {
      // Ignore logging errors to prevent infinite loops
    });
  }
}
```

## Security Considerations

### Sensitive Data Filtering

Never log sensitive information:

```php
// Don't log passwords, tokens, or other sensitive data
$this->logger->info('User login attempt', [
    'username' => $username,
    // 'password' => $password, // NEVER DO THIS
    'ip_address' => $ipAddress,
]);
```

Use processors to filter sensitive data:

```php
class SensitiveDataProcessor {
    public function __invoke(array $record): array {
        // Remove sensitive fields from context
        unset($record['context']['password']);
        unset($record['context']['token']);
        unset($record['context']['credit_card']);
        
        // Mask sensitive values
        if (isset($record['context']['email'])) {
            $record['context']['email'] = $this->maskEmail($record['context']['email']);
        }
        
        return $record;
    }
}
```

### Log Injection Prevention

Prevent log injection attacks by sanitizing input:

```php
// Sanitize user input before logging
$username = str_replace(["\n", "\r"], '', $username);
$message = str_replace(["\n", "\r"], '', $message);

$this->logger->info('User action', [
    'username' => $username,
    'message' => $message,
]);
```

## Performance Considerations

### Asynchronous Logging

For high-throughput applications, consider asynchronous logging:

```php
// Use background queue for logging
$this->queue->push(new LogJob($level, $message, $context));
```

### Log Level Optimization

Adjust log levels based on environment:

```php
// In configuration
'log_level' => $_ENV['APP_ENV'] === 'production' ? 'warning' : 'debug',
```

### Conditional Logging

Avoid expensive operations in log messages:

```php
// Don't do this - json_encode() is called even when logging is disabled
$this->logger->debug('User data: ' . json_encode($userData));

// Do this instead - use context which is only processed when logging
$this->logger->debug('User data loaded', ['user_data' => $userData]);
```

## Monitoring and Analysis

### Centralized Logging

In production, aggregate logs to centralized systems:

```php
// Send logs to ELK stack, Splunk, or cloud logging service
$handler = new SyslogHandler('myapp', LOG_USER, Logger::WARNING);
$logger->pushHandler($handler);
```

### Log Aggregation

Structure logs for easy aggregation:

```php
// Include metrics-friendly fields
$this->logger->info('API request processed', [
    'endpoint' => '/api/users',
    'method' => 'GET',
    'status' => 200,
    'duration_ms' => $duration,
    'user_id' => $userId,
    'correlation_id' => $correlationId,
]);
```

### Alerting

Set up alerts for critical log patterns:

```php
// Monitor for error spikes
// Alert when error rate exceeds threshold
// Notify on critical errors immediately
```

## Best Practices

### Backend Best Practices

1. **Use appropriate log levels** - Don't log everything at ERROR level
2. **Include contextual information** - Make logs actionable with relevant context
3. **Structure log data** - Use arrays/objects instead of concatenated strings
4. **Sanitize sensitive data** - Never log passwords, tokens, or PII
5. **Use correlation IDs** - Enable request tracing across services
6. **Rotate logs regularly** - Prevent disk space issues
7. **Monitor log volume** - Watch for sudden increases that may indicate issues

### Frontend Best Practices

1. **Minimize production logging** - Reduce noise in production environments
2. **Include user context** - Help with reproducing issues
3. **Handle logging errors gracefully** - Prevent infinite loops
4. **Respect user privacy** - Don't log personal information
5. **Use consistent formatting** - Make logs easy to parse and analyze
6. **Send important logs to backend** - Enable centralized monitoring
7. **Include performance metrics** - Log load times and API response times

## Troubleshooting

### Common Issues

1. **Missing context** - Ensure all relevant information is included in log messages
2. **Log flooding** - Adjust log levels or add sampling for high-volume logs
3. **Disk space issues** - Configure proper log rotation and retention
4. **Performance impact** - Use asynchronous logging for high-throughput applications
5. **Sensitive data exposure** - Review log content for PII or credentials

### Debugging Tips

1. **Check correlation IDs** - Trace requests across services using correlation IDs
2. **Review log context** - Look for relevant context data in log messages
3. **Verify log levels** - Ensure appropriate log levels are configured for the environment
4. **Monitor log volume** - Watch for unusual increases in log volume
5. **Test log aggregation** - Verify logs are properly formatted for analysis tools