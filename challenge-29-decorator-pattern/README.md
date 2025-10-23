# Challenge 29: Decorator Pattern

## Description
This challenge focuses on implementing the Decorator design pattern to create a middleware pipeline for HTTP requests. You'll learn to add responsibilities to objects dynamically without affecting other objects.

## Learning Objectives
- Implement the Decorator design pattern
- Add responsibilities to objects dynamically
- Create flexible and extensible middleware systems
- Understand wrapper and component relationships
- Implement chain of responsibility with decorators
- Maintain open/closed principle

## Requirements
- PHP 8.1+
- Composer
- Understanding of design patterns
- Knowledge of HTTP middleware concepts

## Features to Implement
1. Component Interface
   - HTTP request handler interface
   - Response processing
   - Middleware chaining

2. Concrete Components
   - Base request handler
   - API endpoint handlers
   - Static file handlers

3. Decorator Base Class
   - Middleware abstraction
   - Next handler reference
   - Request processing delegation

4. Concrete Decorators
   - Authentication middleware
   - Logging middleware
   - Rate limiting middleware
   - CORS middleware
   - Compression middleware

## Project Structure
```
challenge-29-decorator-pattern/
├── backend-php/
│   ├── config/
│   ├── public/
│   ├── src/
│   │   ├── Component/
│   │   │   ├── RequestHandlerInterface.php
│   │   │   └── BaseRequestHandler.php
│   │   ├── Decorator/
│   │   │   ├── MiddlewareDecorator.php
│   │   │   ├── AuthMiddleware.php
│   │   │   ├── LoggingMiddleware.php
│   │   │   ├── RateLimitMiddleware.php
│   │   │   ├── CorsMiddleware.php
│   │   │   └── CompressionMiddleware.php
│   │   └── Service/
│   │       └── MiddlewareService.php
│   ├── tests/
│   ├── composer.json
│   └── server.php
├── frontend-react/
│   ├── src/
│   │   ├── components/
│   │   │   ├── MiddlewareDemo.jsx
│   │   │   └── PipelineVisualizer.jsx
│   │   └── services/
│   │       └── middlewareService.js
│   ├── package.json
│   └── vite.config.js
└── README.md
```

## Setup Instructions
1. Navigate to the `backend-php` directory
2. Run `composer install` to install dependencies
3. Copy `.env.example` to `.env` and configure your settings
4. Start the development server with `php server.php`
5. Navigate to the `frontend-react` directory
6. Run `npm install` to install frontend dependencies
7. Run `npm run dev` to start the frontend development server

## API Endpoints
- `GET /api/middleware/list` - List available middleware
- `POST /api/middleware/apply` - Apply middleware to request
- `GET /api/pipeline/config` - Get pipeline configuration
- `POST /api/request/process` - Process request through middleware pipeline
- `GET /api/metrics/middleware` - Get middleware performance metrics

## Evaluation Criteria
- Proper implementation of Decorator pattern interfaces
- Effective middleware chaining mechanism
- Dynamic addition/removal of responsibilities
- Clean separation of concerns
- Robust error handling in middleware
- Comprehensive test coverage

## Resources
- [Decorator Pattern](https://en.wikipedia.org/wiki/Decorator_pattern)
- [PHP Design Patterns](https://designpatternsphp.readthedocs.io/en/latest/Structural/Decorator/README.html)
- [Middleware in HTTP Applications](https://www.php-fig.org/psr/psr-15/)