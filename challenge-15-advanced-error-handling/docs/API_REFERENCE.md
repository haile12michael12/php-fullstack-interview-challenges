# API Reference

This document provides detailed information about the backend API endpoints, including request/response formats, error codes, and usage examples.

## Base URL

```
http://localhost:8000/api
```

In production, this would be:
```
https://yourdomain.com/api
```

## Authentication

Most endpoints require authentication via JWT tokens. Include the token in the Authorization header:

```
Authorization: Bearer <token>
```

## Error Response Format

All error responses follow a consistent format:

```json
{
  "error": {
    "type": "ERROR_TYPE",
    "message": "Human-readable error message",
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

## Error Types

| Error Type | HTTP Status | Description |
|------------|-------------|-------------|
| VALIDATION_ERROR | 422 | Input validation failed |
| AUTHENTICATION_ERROR | 401 | Invalid or missing authentication |
| AUTHORIZATION_ERROR | 403 | Insufficient permissions |
| NOT_FOUND_ERROR | 404 | Resource not found |
| DATABASE_ERROR | 500 | Database operation failed |
| EXTERNAL_SERVICE_ERROR | 502 | External service error |
| SERVICE_UNAVAILABLE | 503 | Service temporarily unavailable |
| APPLICATION_ERROR | 500 | Unexpected application error |

## Health Check Endpoints

### GET /health

Check the overall health of the application.

**Response:**
```json
{
  "status": "healthy",
  "timestamp": "2023-01-01T12:00:00Z",
  "services": {
    "database": "healthy",
    "cache": "healthy",
    "external_api": "healthy"
  }
}
```

### GET /health/database

Check database connectivity.

**Response:**
```json
{
  "status": "healthy",
  "timestamp": "2023-01-01T12:00:00Z",
  "latency_ms": 5
}
```

### GET /health/external-services

Check external service connectivity.

**Response:**
```json
{
  "status": "healthy",
  "timestamp": "2023-01-01T12:00:00Z",
  "services": {
    "payment_processor": "healthy",
    "email_service": "healthy"
  }
}
```

## Error Testing Endpoints

### GET /error-test/exception

Trigger a generic application exception.

**Response (500):**
```json
{
  "error": {
    "type": "APPLICATION_ERROR",
    "message": "This is a test exception",
    "code": 500,
    "correlation_id": "abc-123-def-456",
    "timestamp": "2023-01-01T12:00:00Z"
  }
}
```

### GET /error-test/validation

Trigger a validation error.

**Response (422):**
```json
{
  "error": {
    "type": "VALIDATION_ERROR",
    "message": "Validation failed",
    "code": 422,
    "details": [
      {
        "field": "test_field",
        "message": "Test validation error"
      }
    ],
    "correlation_id": "abc-123-def-456",
    "timestamp": "2023-01-01T12:00:00Z"
  }
}
```

### GET /error-test/database

Trigger a database exception.

**Response (500):**
```json
{
  "error": {
    "type": "DATABASE_ERROR",
    "message": "Database connection failed",
    "code": 500,
    "correlation_id": "abc-123-def-456",
    "timestamp": "2023-01-01T12:00:00Z"
  }
}
```

### GET /error-test/external-service

Trigger an external service exception.

**Response (502):**
```json
{
  "error": {
    "type": "EXTERNAL_SERVICE_ERROR",
    "message": "External service unavailable",
    "code": 502,
    "correlation_id": "abc-123-def-456",
    "timestamp": "2023-01-01T12:00:00Z"
  }
}
```

### GET /error-test/unauthorized

Trigger an authentication error.

**Response (401):**
```json
{
  "error": {
    "type": "AUTHENTICATION_ERROR",
    "message": "Unauthorized",
    "code": 401,
    "correlation_id": "abc-123-def-456",
    "timestamp": "2023-01-01T12:00:00Z"
  }
}
```

### GET /error-test/forbidden

Trigger an authorization error.

**Response (403):**
```json
{
  "error": {
    "type": "AUTHORIZATION_ERROR",
    "message": "Forbidden",
    "code": 403,
    "correlation_id": "abc-123-def-456",
    "timestamp": "2023-01-01T12:00:00Z"
  }
}
```

### GET /error-test/not-found

Trigger a not found error.

**Response (404):**
```json
{
  "error": {
    "type": "NOT_FOUND_ERROR",
    "message": "Resource not found",
    "code": 404,
    "correlation_id": "abc-123-def-456",
    "timestamp": "2023-01-01T12:00:00Z"
  }
}
```

### GET /error-test/service-unavailable

Trigger a service unavailable error.

**Response (503):**
```json
{
  "error": {
    "type": "SERVICE_UNAVAILABLE",
    "message": "Service temporarily unavailable",
    "code": 503,
    "correlation_id": "abc-123-def-456",
    "timestamp": "2023-01-01T12:00:00Z"
  }
}
```

## Recovery Testing Endpoints

### GET /recovery-test/retry-success

Test retry mechanism with eventual success.

**Response:**
```json
{
  "message": "Success after retry",
  "attempts": 3
}
```

### GET /recovery-test/retry-failure

Test retry mechanism with ultimate failure.

**Response (500):**
```json
{
  "error": {
    "type": "EXTERNAL_SERVICE_ERROR",
    "message": "Service failed after maximum retries",
    "code": 500,
    "attempts": 4,
    "correlation_id": "abc-123-def-456",
    "timestamp": "2023-01-01T12:00:00Z"
  }
}
```

### GET /recovery-test/circuit-breaker

Test circuit breaker functionality.

**Response (varies):**
- First 5 calls: Success
- Next calls (until timeout): 503 Service Unavailable
- After timeout: Success (circuit half-open)

### GET /recovery-test/fallback

Test fallback strategy.

**Response:**
```json
{
  "data": "Fallback data",
  "is_fallback": true
}
```

## User Management Endpoints

### POST /users

Create a new user.

**Request:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "securepassword"
}
```

**Response (201):**
```json
{
  "id": 123,
  "name": "John Doe",
  "email": "john@example.com",
  "created_at": "2023-01-01T12:00:00Z"
}
```

**Errors:**
- 422: Validation failed
- 500: Database error

### GET /users/{id}

Get user by ID.

**Response (200):**
```json
{
  "id": 123,
  "name": "John Doe",
  "email": "john@example.com",
  "created_at": "2023-01-01T12:00:00Z"
}
```

**Errors:**
- 404: User not found
- 500: Database error

### PUT /users/{id}

Update user information.

**Request:**
```json
{
  "name": "John Smith",
  "email": "johnsmith@example.com"
}
```

**Response (200):**
```json
{
  "id": 123,
  "name": "John Smith",
  "email": "johnsmith@example.com",
  "updated_at": "2023-01-01T13:00:00Z"
}
```

**Errors:**
- 404: User not found
- 422: Validation failed
- 500: Database error

### DELETE /users/{id}

Delete a user.

**Response (204):** No content

**Errors:**
- 404: User not found
- 500: Database error

## Product Management Endpoints

### POST /products

Create a new product.

**Request:**
```json
{
  "name": "Product Name",
  "description": "Product description",
  "price": 29.99,
  "category_id": 1
}
```

**Response (201):**
```json
{
  "id": 456,
  "name": "Product Name",
  "description": "Product description",
  "price": 29.99,
  "category_id": 1,
  "created_at": "2023-01-01T12:00:00Z"
}
```

**Errors:**
- 422: Validation failed
- 500: Database error

### GET /products

Get list of products.

**Query Parameters:**
- `page` (optional): Page number (default: 1)
- `limit` (optional): Items per page (default: 20, max: 100)
- `category_id` (optional): Filter by category

**Response (200):**
```json
{
  "data": [
    {
      "id": 456,
      "name": "Product Name",
      "description": "Product description",
      "price": 29.99,
      "category_id": 1,
      "created_at": "2023-01-01T12:00:00Z"
    }
  ],
  "pagination": {
    "page": 1,
    "limit": 20,
    "total": 1,
    "pages": 1
  }
}
```

**Errors:**
- 500: Database error

### GET /products/{id}

Get product by ID.

**Response (200):**
```json
{
  "id": 456,
  "name": "Product Name",
  "description": "Product description",
  "price": 29.99,
  "category_id": 1,
  "created_at": "2023-01-01T12:00:00Z"
}
```

**Errors:**
- 404: Product not found
- 500: Database error

### PUT /products/{id}

Update product information.

**Request:**
```json
{
  "name": "Updated Product Name",
  "description": "Updated product description",
  "price": 39.99
}
```

**Response (200):**
```json
{
  "id": 456,
  "name": "Updated Product Name",
  "description": "Updated product description",
  "price": 39.99,
  "updated_at": "2023-01-01T13:00:00Z"
}
```

**Errors:**
- 404: Product not found
- 422: Validation failed
- 500: Database error

### DELETE /products/{id}

Delete a product.

**Response (204):** No content

**Errors:**
- 404: Product not found
- 500: Database error

## Order Management Endpoints

### POST /orders

Create a new order.

**Request:**
```json
{
  "user_id": 123,
  "items": [
    {
      "product_id": 456,
      "quantity": 2,
      "price": 29.99
    }
  ],
  "total_amount": 59.98
}
```

**Response (201):**
```json
{
  "id": 789,
  "user_id": 123,
  "status": "pending",
  "total_amount": 59.98,
  "created_at": "2023-01-01T12:00:00Z"
}
```

**Errors:**
- 422: Validation failed
- 500: Database error

### GET /orders/{id}

Get order by ID.

**Response (200):**
```json
{
  "id": 789,
  "user_id": 123,
  "status": "pending",
  "total_amount": 59.98,
  "items": [
    {
      "product_id": 456,
      "quantity": 2,
      "price": 29.99
    }
  ],
  "created_at": "2023-01-01T12:00:00Z"
}
```

**Errors:**
- 404: Order not found
- 500: Database error

### PUT /orders/{id}/status

Update order status.

**Request:**
```json
{
  "status": "completed"
}
```

**Response (200):**
```json
{
  "id": 789,
  "user_id": 123,
  "status": "completed",
  "total_amount": 59.98,
  "updated_at": "2023-01-01T13:00:00Z"
}
```

**Errors:**
- 404: Order not found
- 422: Invalid status
- 500: Database error

## External Service Integration Endpoints

### POST /payments

Process a payment.

**Request:**
```json
{
  "order_id": 789,
  "amount": 59.98,
  "payment_method": "credit_card",
  "card_token": "tok_123456"
}
```

**Response (200):**
```json
{
  "id": "pay_123456",
  "order_id": 789,
  "status": "succeeded",
  "amount": 59.98,
  "processed_at": "2023-01-01T12:05:00Z"
}
```

**Errors:**
- 422: Validation failed
- 502: External payment service error
- 500: Database error

### POST /emails

Send an email.

**Request:**
```json
{
  "to": "user@example.com",
  "subject": "Order Confirmation",
  "template": "order_confirmation",
  "variables": {
    "order_id": 789,
    "total_amount": 59.98
  }
}
```

**Response (200):**
```json
{
  "id": "email_123456",
  "to": "user@example.com",
  "status": "sent",
  "sent_at": "2023-01-01T12:05:00Z"
}
```

**Errors:**
- 422: Validation failed
- 502: External email service error
- 500: Database error

## Monitoring Endpoints

### GET /metrics

Get application metrics.

**Response (200):**
```json
{
  "requests": {
    "total": 12345,
    "errors": 45,
    "error_rate": 0.0036
  },
  "recovery": {
    "retries": {
      "total": 123,
      "successful": 110,
      "failed": 13
    },
    "circuit_breakers": {
      "tripped": 5,
      "reset": 3
    },
    "fallbacks": {
      "triggered": 8
    }
  },
  "performance": {
    "avg_response_time_ms": 125,
    "p95_response_time_ms": 350,
    "p99_response_time_ms": 800
  }
}
```

### GET /logs

Get recent log entries (requires admin privileges).

**Query Parameters:**
- `level` (optional): Filter by log level (error, warn, info, debug)
- `limit` (optional): Number of entries (default: 50, max: 1000)

**Response (200):**
```json
{
  "logs": [
    {
      "timestamp": "2023-01-01T12:00:00Z",
      "level": "error",
      "message": "Database connection failed",
      "context": {
        "host": "localhost",
        "database": "myapp"
      }
    }
  ]
}
```

## Rate Limiting

API endpoints are rate-limited to prevent abuse:

- **Anonymous users**: 100 requests per hour
- **Authenticated users**: 1000 requests per hour
- **Admin users**: 5000 requests per hour

Exceeding rate limits results in a 429 Too Many Requests response:

```json
{
  "error": {
    "type": "RATE_LIMIT_ERROR",
    "message": "Too many requests",
    "code": 429,
    "retry_after": 3600,
    "correlation_id": "abc-123-def-456",
    "timestamp": "2023-01-01T12:00:00Z"
  }
}
```

## CORS Policy

The API supports CORS with the following policy:

- **Allowed origins**: Configured in [config/app.php](../backend-php/config/app.php)
- **Allowed methods**: GET, POST, PUT, DELETE, OPTIONS
- **Allowed headers**: Authorization, Content-Type, X-Correlation-ID
- **Exposed headers**: X-Correlation-ID
- **Max age**: 86400 seconds (24 hours)

## Security Headers

Responses include security headers:

```
Strict-Transport-Security: max-age=31536000; includeSubDomains
X-Content-Type-Options: nosniff
X-Frame-Options: DENY
X-XSS-Protection: 1; mode=block
Content-Security-Policy: default-src 'self'
```

## Versioning

API versioning is handled through the URL path:

```
/api/v1/endpoint  # Version 1
/api/v2/endpoint  # Version 2
```

Currently, only v1 is available.

## Deprecation Policy

Deprecated endpoints will be marked with a warning header:

```
Warning: 299 - "This endpoint is deprecated and will be removed in version 2.0"
```

Deprecated endpoints will be supported for at least 6 months after the release of the version that deprecates them.

## Example Usage

### Creating a User

```bash
curl -X POST http://localhost:8000/api/users \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "securepassword"
  }'
```

### Handling Errors

```javascript
// JavaScript example
try {
  const response = await fetch('/api/users', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(userData),
  });
  
  if (!response.ok) {
    const errorData = await response.json();
    throw new ApiError(errorData.error.message, response.status, errorData);
  }
  
  const user = await response.json();
  console.log('User created:', user);
} catch (error) {
  if (error instanceof ApiError) {
    console.error('API Error:', error.message);
    // Handle specific error types
    switch (error.status) {
      case 422:
        console.error('Validation errors:', error.data.details);
        break;
      case 500:
        console.error('Server error, please try again later');
        break;
    }
  } else {
    console.error('Network error:', error.message);
  }
}
```

## Troubleshooting

### Common Issues

1. **CORS errors** - Ensure the frontend origin is in the allowed origins list
2. **Authentication failures** - Verify JWT token is valid and not expired
3. **Rate limiting** - Check if you're exceeding request limits
4. **Validation errors** - Review request payload against API requirements
5. **Service unavailable** - Check if external services are down

### Debugging Tips

1. **Check correlation IDs** - Use correlation IDs to trace requests in logs
2. **Review error details** - Look at validation error details for specific issues
3. **Monitor metrics** - Check API metrics for unusual patterns
4. **Test with curl** - Use curl to test endpoints directly
5. **Enable debug logging** - Set log level to debug for detailed information