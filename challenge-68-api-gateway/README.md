# Challenge 68: API Gateway

## Description
In this challenge, you'll build an API gateway that acts as a single entry point for all client requests, routing them to appropriate backend services. API gateways provide essential features like authentication, rate limiting, and request/response transformation.

## Learning Objectives
- Understand API gateway patterns and benefits
- Implement request routing and load balancing
- Create authentication and authorization layers
- Handle rate limiting and throttling
- Implement request/response transformation
- Manage service discovery

## Requirements
Create an API gateway with the following features:

1. **Routing and Load Balancing**:
   - Route requests to appropriate backend services
   - Load balancing between multiple service instances
   - Path-based and header-based routing
   - Service registry integration
   - Health check monitoring
   - Circuit breaker pattern

2. **Security Features**:
   - Authentication and authorization
   - JWT token validation
   - API key management
   - SSL/TLS termination
   - Request validation
   - IP filtering and blocking

3. **Traffic Management**:
   - Rate limiting and throttling
   - Request/response caching
   - Compression and decompression
   - Timeout handling
   - Retry mechanisms
   - Request queuing

4. **Transformation and Monitoring**:
   - Request/response transformation
   - Protocol translation (HTTP to gRPC)
   - Logging and monitoring
   - Metrics collection
   - Distributed tracing
   - Error handling and fallback

## Features to Implement
- [ ] Request routing to backend services
- [ ] Load balancing between service instances
- [ ] Authentication and authorization
- [ ] JWT token validation
- [ ] Rate limiting and throttling
- [ ] Request/response caching
- [ ] Request/response transformation
- [ ] Service discovery integration
- [ ] Health check monitoring
- [ ] Circuit breaker pattern
- [ ] Logging and metrics collection
- [ ] Error handling and fallback

## Project Structure
```
backend-php/
├── src/
│   ├── Gateway/
│   │   ├── ApiGateway.php
│   │   ├── Router.php
│   │   ├── LoadBalancer.php
│   │   ├── ServiceRegistry.php
│   │   ├── Middleware/
│   │   │   ├── AuthMiddleware.php
│   │   │   ├── RateLimitMiddleware.php
│   │   │   ├── LoggingMiddleware.php
│   │   │   └── TransformMiddleware.php
│   │   └── Handlers/
│   │       ├── JwtHandler.php
│   │       ├── CacheHandler.php
│   │       └── CircuitBreaker.php
│   ├── Http/
│   │   ├── Request.php
│   │   ├── Response.php
│   │   └── HttpClient.php
│   └── Services/
│       ├── UserService.php
│       ├── PostService.php
│       └── NotificationService.php
├── public/
│   └── index.php
├── config/
│   └── gateway.php
└── vendor/

frontend-react/
├── src/
│   ├── api/
│   │   └── gateway.js
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

## Gateway Configuration Example
```yaml
services:
  user-service:
    url: http://localhost:3001
    instances:
      - http://localhost:3001
      - http://localhost:3002
    health_check: /health
    timeout: 5000

  post-service:
    url: http://localhost:3003
    instances:
      - http://localhost:3003
      - http://localhost:3004
    health_check: /health
    timeout: 5000

routes:
  /api/users:
    service: user-service
    methods: [GET, POST, PUT, DELETE]
    auth_required: true

  /api/posts:
    service: post-service
    methods: [GET, POST, PUT, DELETE]
    auth_required: true

rate_limits:
  default: 1000 requests/hour
  authenticated: 5000 requests/hour
  api_key: 10000 requests/hour
```

## API Endpoints
```
# User Service (routed through gateway)
GET    /api/users
POST   /api/users
GET    /api/users/{id}
PUT    /api/users/{id}
DELETE /api/users/{id}

# Post Service (routed through gateway)
GET    /api/posts
POST   /api/posts
GET    /api/posts/{id}
PUT    /api/posts/{id}
DELETE /api/posts/{id}

# Gateway Management
GET    /gateway/health
GET    /gateway/stats
GET    /gateway/services
POST   /gateway/services
```

## Gateway Features
- **Authentication**: JWT-based authentication with token validation
- **Rate Limiting**: Per-client and per-API key rate limiting
- **Caching**: Response caching for GET requests
- **Load Balancing**: Round-robin load balancing between service instances
- **Health Checks**: Automatic health checking of backend services
- **Circuit Breaker**: Fail-fast mechanism for unhealthy services
- **Logging**: Detailed request/response logging
- **Metrics**: Request count, response time, and error rate metrics

## Evaluation Criteria
- [ ] Request routing works correctly
- [ ] Load balancing distributes traffic
- [ ] Authentication and authorization function
- [ ] Rate limiting prevents abuse
- [ ] Request/response caching improves performance
- [ ] Service discovery integrates properly
- [ ] Health checks monitor backend services
- [ ] Circuit breaker protects against failures
- [ ] Logging and metrics are comprehensive
- [ ] Code is well-organized and documented
- [ ] Tests cover gateway functionality

## Resources
- [API Gateway Pattern](https://microservices.io/patterns/apigateway.html)
- [AWS API Gateway](https://aws.amazon.com/api-gateway/)
- [Kong API Gateway](https://konghq.com/kong/)
- [Netflix Zuul](https://github.com/Netflix/zuul)