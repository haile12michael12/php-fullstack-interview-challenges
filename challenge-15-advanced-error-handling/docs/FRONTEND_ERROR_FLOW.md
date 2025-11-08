# Frontend Error Flow Documentation

This document describes how errors flow through the frontend application, from occurrence to handling and recovery.

## Error Flow Overview

The frontend implements a comprehensive error handling flow that captures, processes, and responds to errors at multiple levels:

```
[Error Occurrence] → [Capture] → [Processing] → [Notification] → [Recovery] → [Logging]
```

## Error Sources

### 1. JavaScript Runtime Errors

Unhandled exceptions in JavaScript code:

```javascript
// Example of an unhandled error
function divide(a, b) {
  return a / b; // Potential division by zero
}

const result = divide(10, 0); // Returns Infinity, but might be unexpected
```

### 2. API/Network Errors

HTTP request failures:

```javascript
// In apiClient.js
try {
  const response = await fetch('/api/users');
  if (!response.ok) {
    throw new ApiError('Failed to fetch users', response.status);
  }
} catch (error) {
  // Network errors, CORS issues, etc.
  throw new ApiError('Network error', 0, { originalError: error });
}
```

### 3. Component Rendering Errors

React component errors that cause rendering failures:

```jsx
class UserProfile extends Component {
  render() {
    const { user } = this.props;
    // Potential error if user is undefined
    return <div>{user.name.toUpperCase()}</div>;
  }
}
```

### 4. User Input Validation Errors

Form validation and user input errors:

```javascript
function validateForm(data) {
  const errors = [];
  
  if (!data.email || !isValidEmail(data.email)) {
    errors.push({ field: 'email', message: 'Invalid email format' });
  }
  
  if (errors.length > 0) {
    throw new ValidationError('Form validation failed', errors);
  }
}
```

## Error Capture Mechanisms

### 1. Error Boundaries

React [ErrorBoundary](../frontend-react/src/components/ErrorBoundary.jsx) components catch rendering errors:

```jsx
class ErrorBoundary extends Component {
  constructor(props) {
    super(props);
    this.state = { hasError: false, error: null };
  }

  static getDerivedStateFromError(error) {
    return { hasError: true };
  }

  componentDidCatch(error, errorInfo) {
    // Capture the error
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

### 2. Try/Catch Blocks

Explicit error handling in async operations:

```javascript
// In service functions
async function fetchUserData(userId) {
  try {
    const userData = await apiClient.get(`/users/${userId}`);
    return userData;
  } catch (error) {
    // Capture and process the error
    errorService.handleApiError(error, { userId, operation: 'fetchUserData' });
    throw error;
  }
}
```

### 3. Promise Error Handling

Handling rejected promises:

```javascript
// Using async/await (preferred)
async function loadDashboardData() {
  try {
    const [users, orders, products] = await Promise.all([
      apiClient.get('/users'),
      apiClient.get('/orders'),
      apiClient.get('/products')
    ]);
    return { users, orders, products };
  } catch (error) {
    errorService.handleApiError(error, { operation: 'loadDashboardData' });
    // Return fallback data or rethrow
    return getFallbackDashboardData();
  }
}

// Using .catch()
apiClient.get('/users')
  .then(users => setUsers(users))
  .catch(error => {
    errorService.handleApiError(error, { operation: 'loadUsers' });
    setError('Failed to load users');
  });
```

## Error Processing

### Error Service

The [errorService](../frontend-react/src/services/errorService.js) centralizes error processing:

```javascript
class ErrorService {
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
      case 422:
        this.handleValidationError(errorInfo);
        break;
      case 500:
        this.handleServerError(errorInfo);
        break;
      case 502:
      case 503:
        this.handleServiceUnavailableError(errorInfo);
        break;
      default:
        this.handleGenericError(errorInfo);
    }

    return errorInfo;
  }
}
```

### Error Classification

Errors are classified based on their type and HTTP status:

1. **Authentication Errors (401)** - User needs to log in
2. **Authorization Errors (403)** - User lacks permissions
3. **Validation Errors (422)** - Input validation failed
4. **Server Errors (500)** - Internal server errors
5. **Service Unavailable (502, 503)** - Temporary service issues
6. **Network Errors (0)** - Connection issues
7. **Application Errors** - JavaScript runtime errors

## Error Notification

### Notification Toast

The [NotificationToast](../frontend-react/src/components/NotificationToast.jsx) component displays error messages to users:

```jsx
const NotificationToast = () => {
  const { errors, removeError } = useErrorContext();
  
  return (
    <div className="notification-container">
      {errors.map(error => (
        <div 
          key={error.id} 
          className={`notification-toast ${error.type.toLowerCase()}`}
        >
          <div className="notification-content">
            <span className="notification-message">{error.message}</span>
            <button 
              className="notification-close"
              onClick={() => removeError(error.id)}
            >
              ×
            </button>
          </div>
        </div>
      ))}
    </div>
  );
};
```

### Context-Based Notifications

Different error types trigger different notifications:

```javascript
// In errorService.js
handleUnauthorizedError(errorInfo) {
  // Redirect to login page
  window.location.href = '/login';
  
  // Show notification
  this.notifyErrorListeners({
    ...errorInfo,
    message: 'Your session has expired. Please log in again.',
    type: 'AUTH_ERROR'
  });
}

handleValidationError(errorInfo) {
  // Show validation errors
  this.notifyErrorListeners({
    ...errorInfo,
    message: 'Please check the form for errors.',
    type: 'VALIDATION_ERROR'
  });
}
```

## Error Recovery

### Retry Mechanisms

The [RetryHandler](../frontend-react/src/services/retryHandler.js) provides automatic retry capabilities:

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

[FallbackView](../frontend-react/src/components/FallbackView.jsx) components provide degraded experiences:

```jsx
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

### Error Pages

[ErrorPage](../frontend-react/src/components/ErrorPage.jsx) components display detailed error information:

```jsx
const ErrorPage = ({ error, errorInfo }) => {
  const { clearGlobalError } = useErrorContext();
  
  return (
    <div className="error-page">
      <h1>Something went wrong</h1>
      <p>We're sorry, but something went wrong. Please try again.</p>
      
      {process.env.NODE_ENV === 'development' && (
        <div className="error-details">
          <h2>Error Details</h2>
          <pre>{error.toString()}</pre>
          <pre>{errorInfo?.componentStack}</pre>
        </div>
      )}
      
      <div className="error-actions">
        <button onClick={() => window.location.reload()}>
          Reload Page
        </button>
        <button onClick={clearGlobalError}>
          Continue
        </button>
      </div>
    </div>
  );
};
```

## Error Context Management

### Error Context Provider

The [ErrorContext](../frontend-react/src/context/ErrorContext.jsx) manages global error state:

```jsx
const errorReducer = (state, action) => {
  switch (action.type) {
    case ERROR_ACTION_TYPES.ADD_ERROR:
      return {
        ...state,
        errors: [...state.errors, action.payload],
      };
    
    case ERROR_ACTION_TYPES.REMOVE_ERROR:
      return {
        ...state,
        errors: state.errors.filter(error => error.id !== action.payload),
      };
    
    case ERROR_ACTION_TYPES.SET_GLOBAL_ERROR:
      return {
        ...state,
        globalError: action.payload,
      };
    
    default:
      return state;
  }
};
```

### Custom Hooks

The [useErrorHandler](../frontend-react/src/hooks/useErrorHandler.js) hook provides convenient error handling:

```javascript
const useErrorHandler = () => {
  const { addError, setGlobalError } = useErrorContext();

  const handleApiError = useCallback((error, context = {}) => {
    const errorInfo = errorService.handleApiError(error, context);
    
    if (errorInfo) {
      addError({
        type: 'API_ERROR',
        ...errorInfo,
      });
    }
    
    return errorInfo;
  }, [addError]);

  const setGlobalAppError = useCallback((error) => {
    const errorInfo = {
      message: error.message,
      stack: error.stack,
      timestamp: new Date().toISOString(),
    };

    setGlobalError(errorInfo);
    errorService.handleGlobalError(error);
  }, [setGlobalError]);

  return {
    handleApiError,
    setGlobalAppError,
  };
};
```

## Error Logging

### Structured Logging

The [logger](../frontend-react/src/utils/logger.js) utility provides structured logging:

```javascript
class Logger {
  error(message, context = {}) {
    if (!this.shouldLog('ERROR')) return;
    
    const formattedMessage = this.formatMessage('ERROR', message, context);
    console.error(formattedMessage);
    
    // In production, send to logging service
    this.sendToLoggingService('ERROR', message, context);
  }
  
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
// In apiClient.js
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

## Error Flow Integration

### Component Integration

Components integrate error handling through hooks:

```jsx
const UserProfilePage = ({ userId }) => {
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const { handleApiError } = useErrorHandler();
  
  useEffect(() => {
    const loadUser = async () => {
      try {
        setLoading(true);
        const userData = await fetchUserData(userId);
        setUser(userData);
      } catch (err) {
        const errorInfo = handleApiError(err, { component: 'UserProfilePage', userId });
        setError(errorInfo);
      } finally {
        setLoading(false);
      }
    };
    
    loadUser();
  }, [userId, handleApiError]);
  
  if (loading) return <LoadingSpinner />;
  if (error) return <FallbackView type="network_error" onRetry={() => window.location.reload()} />;
  
  return <UserProfile user={user} />;
};
```

### Service Integration

Services integrate with error handling and recovery:

```javascript
class UserService {
  constructor() {
    this.apiClient = new ApiClient();
    this.retryHandler = new RetryHandler();
  }
  
  async getUserProfile(userId) {
    return this.retryHandler.execute(
      () => this.apiClient.get(`/users/${userId}`),
      (error) => error.status >= 500 || error.status === 0
    );
  }
}
```

## Error Handling Patterns

### 1. Fail Fast Pattern

Immediately handle errors that can't be recovered from:

```javascript
function validateRequiredFields(data, requiredFields) {
  const missingFields = requiredFields.filter(field => !data[field]);
  
  if (missingFields.length > 0) {
    throw new ValidationError('Missing required fields', 
      missingFields.map(field => ({ field, message: `${field} is required` }))
    );
  }
}
```

### 2. Graceful Degradation Pattern

Provide fallback functionality when primary features fail:

```javascript
async function loadDashboardData() {
  try {
    const primaryData = await loadPrimaryDashboardData();
    return primaryData;
  } catch (error) {
    logger.warn('Primary dashboard data failed, using fallback', { error });
    
    try {
      const fallbackData = await loadFallbackDashboardData();
      return {
        ...fallbackData,
        isFallback: true
      };
    } catch (fallbackError) {
      logger.error('Both primary and fallback dashboard data failed', { 
        primaryError: error,
        fallbackError: fallbackError
      });
      
      throw new ApplicationError('Unable to load dashboard data');
    }
  }
}
```

### 3. Circuit Breaker Pattern (Frontend)

Prevent repeated failed requests:

```javascript
class FrontendCircuitBreaker {
  constructor(timeout = 60000, failureThreshold = 5) {
    this.timeout = timeout;
    this.failureThreshold = failureThreshold;
    this.failureCount = 0;
    this.lastFailureTime = null;
  }
  
  async call(operation) {
    // Check if circuit is open
    if (this.isOpen()) {
      throw new ServiceUnavailableError('Service temporarily unavailable');
    }
    
    try {
      const result = await operation();
      this.onSuccess();
      return result;
    } catch (error) {
      this.onFailure();
      throw error;
    }
  }
  
  isOpen() {
    return this.failureCount >= this.failureThreshold && 
           Date.now() - this.lastFailureTime < this.timeout;
  }
  
  onFailure() {
    this.failureCount++;
    this.lastFailureTime = Date.now();
  }
  
  onSuccess() {
    this.failureCount = 0;
    this.lastFailureTime = null;
  }
}
```

## Testing Error Flows

### Unit Testing Error Handling

```javascript
// ErrorService.test.js
describe('ErrorService', () => {
  let errorService;
  let mockLogger;
  
  beforeEach(() => {
    mockLogger = { error: jest.fn() };
    errorService = new ErrorService(mockLogger);
  });
  
  describe('handleApiError', () => {
    it('should handle 401 errors', () => {
      const error = new ApiError('Unauthorized', 401);
      const mockListener = jest.fn();
      
      errorService.addErrorListener(mockListener);
      errorService.handleApiError(error);
      
      expect(mockListener).toHaveBeenCalledWith(
        expect.objectContaining({
          status: 401,
          message: 'Unauthorized'
        })
      );
    });
  });
});
```

### Integration Testing Error Flows

```javascript
// UserProfilePage.test.js
describe('UserProfilePage', () => {
  it('should show fallback view when API fails', async () => {
    // Mock API failure
    fetch.mockRejectedValueOnce(new ApiError('Network error', 0));
    
    render(<UserProfilePage userId="123" />);
    
    // Wait for error handling
    await waitFor(() => {
      expect(screen.getByText('Network Error')).toBeInTheDocument();
    });
    
    // Test retry functionality
    fireEvent.click(screen.getByText('Retry'));
    
    // Verify retry behavior
    expect(fetch).toHaveBeenCalledTimes(2);
  });
});
```

## Monitoring and Observability

### Error Metrics

Track key error metrics in the frontend:

```javascript
class FrontendMetrics {
  recordError(type, context = {}) {
    // In production, send to analytics service
    if (process.env.NODE_ENV === 'production') {
      analytics.track('error_occurred', {
        type,
        context,
        timestamp: new Date().toISOString(),
        url: window.location.href,
        userAgent: navigator.userAgent,
      });
    }
  }
  
  recordRecovery(type, success) {
    analytics.track('error_recovery', {
      type,
      success,
      timestamp: new Date().toISOString(),
    });
  }
}
```

### Performance Impact Monitoring

Monitor the performance impact of error handling:

```javascript
// Measure error handling overhead
const startTime = performance.now();
errorService.handleApiError(error, context);
const endTime = performance.now();

logger.debug('Error handling duration', {
  duration: endTime - startTime,
  errorType: error.constructor.name,
});
```

## Best Practices

### 1. User Experience

- Provide clear, actionable error messages
- Avoid technical jargon in user-facing messages
- Offer recovery options when possible
- Maintain context during error recovery
- Use appropriate visual indicators for different error types

### 2. Developer Experience

- Log errors with sufficient context for debugging
- Use consistent error handling patterns
- Implement centralized error processing
- Provide detailed error information in development
- Make error handling easily testable

### 3. Security

- Sanitize error messages to prevent information leakage
- Don't expose internal system details to users
- Validate and sanitize all error data
- Implement rate limiting for error reporting
- Protect against error-based denial of service

### 4. Performance

- Minimize overhead in error handling paths
- Avoid expensive operations in error handlers
- Implement efficient logging strategies
- Use lazy evaluation for detailed error information
- Cache fallback data when appropriate

## Troubleshooting

### Common Issues

1. **Errors not being caught** - Ensure all async operations are properly wrapped
2. **Error messages not displayed** - Check notification system integration
3. **Recovery mechanisms not working** - Verify retry conditions and fallback logic
4. **Performance degradation** - Monitor error handling overhead
5. **Information leakage** - Review error messages for sensitive data

### Debugging Tips

1. **Enable detailed logging** - Set log level to DEBUG in development
2. **Use browser developer tools** - Monitor network requests and console errors
3. **Test error scenarios** - Manually trigger different error conditions
4. **Review error metrics** - Analyze error patterns and frequencies
5. **Check correlation IDs** - Trace errors from frontend to backend