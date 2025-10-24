# Challenge 60: Middleware Pipeline

## Description
In this challenge, you'll build a middleware pipeline that processes HTTP requests and responses through a series of layers. This pattern is essential for implementing cross-cutting concerns like authentication, logging, and request validation.

## Learning Objectives
- Understand middleware architecture patterns
- Implement a pipeline processing system
- Create middleware components for common tasks
- Handle request/response transformation
- Implement conditional middleware execution
- Support middleware grouping and ordering

## Requirements
Create a middleware pipeline system with the following features:

1. **Pipeline Architecture**:
   - Request/response processing through middleware layers
   - Double-pass middleware (request, response, next)
   - Single-pass middleware (request, next)
   - Middleware termination and short-circuiting

2. **Middleware Types**:
   - Authentication middleware
   - Logging middleware
   - Validation middleware
   - CORS middleware
   - Rate limiting middleware
   - Error handling middleware

3. **Pipeline Management**:
   - Middleware registration and ordering
   - Conditional middleware execution
   - Middleware grouping for routes
   - Pipeline branching and merging
   - Middleware configuration

4. **Advanced Features**:
   - Middleware priority and precedence
   - Middleware parameters and configuration
   - Pipeline introspection and debugging
   - Middleware reuse across different pipelines
   - Performance monitoring

## Features to Implement
- [ ] Middleware interface and base class
- [ ] Pipeline execution with request/response flow
- [ ] Authentication middleware
- [ ] Logging middleware
- [ ] Validation middleware
- [ ] CORS middleware
- [ ] Rate limiting middleware
- [ ] Error handling middleware
- [ ] Conditional middleware execution
- [ ] Middleware grouping and ordering
- [ ] Pipeline introspection

## Project Structure
```
backend-php/
├── src/
│   ├── Middleware/
│   │   ├── MiddlewareInterface.php
│   │   ├── Pipeline.php
│   │   ├── AuthMiddleware.php
│   │   ├── LogMiddleware.php
│   │   ├── ValidationMiddleware.php
│   │   ├── CorsMiddleware.php
│   │   ├── RateLimitMiddleware.php
│   │   └── ErrorHandlerMiddleware.php
│   ├── Http/
│   │   ├── Request.php
│   │   ├── Response.php
│   │   └── ServerRequest.php
│   └── Exceptions/
├── public/
│   └── index.php
├── config/
│   └── middleware.php
└── vendor/

frontend-react/
├── src/
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

## API Endpoints
- `GET /pipeline` - Get pipeline configuration
- `POST /pipeline/middleware` - Add middleware to pipeline
- `DELETE /pipeline/middleware/{name}` - Remove middleware
- `POST /pipeline/process` - Process a request through pipeline

## Example Middleware
- `AuthMiddleware` - Check user authentication
- `LogMiddleware` - Log request and response details
- `ValidationMiddleware` - Validate request data
- `CorsMiddleware` - Handle CORS headers
- `RateLimitMiddleware` - Limit request frequency
- `ErrorHandlerMiddleware` - Handle exceptions

## Evaluation Criteria
- [ ] Pipeline correctly processes requests through middleware
- [ ] Middleware executes in correct order
- [ ] Request/response transformation works
- [ ] Short-circuiting stops pipeline execution
- [ ] Conditional middleware executes properly
- [ ] Error handling middleware catches exceptions
- [ ] Middleware grouping works for routes
- [ ] Code is well-organized and documented
- [ ] Tests cover pipeline functionality

## Resources
- [Middleware Pattern](https://en.wikipedia.org/wiki/Middleware)
- [PSR-15 HTTP Server Request Handlers](https://www.php-fig.org/psr/psr-15/)
- [Laravel Middleware](https://laravel.com/docs/middleware)
- [Express.js Middleware](https://expressjs.com/en/guide/using-middleware.html)