# Challenge 44: Rate Limiting

## Description
This challenge focuses on implementing rate limiting with sliding window algorithm. You'll learn to protect APIs from abuse and ensure fair resource usage.

## Learning Objectives
- Implement rate limiting mechanisms
- Use sliding window algorithm for accurate limiting
- Protect APIs from abuse
- Ensure fair resource usage
- Understand rate limiting patterns
- Handle rate limit exceeded scenarios

## Requirements
- PHP 8.1+
- Composer
- Understanding of API security
- Knowledge of rate limiting algorithms

## Features to Implement
1. Rate Limiter Core
   - Sliding window algorithm
   - Token bucket algorithm
   - Leaky bucket algorithm
   - Fixed window counter

2. Limit Configuration
   - Per-user limits
   - Per-endpoint limits
   - Global limits
   - Dynamic limit adjustment

3. Storage Backends
   - In-memory storage
   - Redis storage
   - Database storage
   - Distributed storage

4. Advanced Features
   - Rate limit headers
   - Retry-after calculation
   - Burst handling
   - Monitoring and metrics

## Project Structure
```
challenge-44-rate-limiting/
├── backend-php/
│   ├── config/
│   ├── public/
│   ├── src/
│   │   ├── RateLimit/
│   │   │   ├── RateLimiter.php
│   │   │   ├── SlidingWindow.php
│   │   │   ├── TokenBucket.php
│   │   │   └── LimitStorage.php
│   │   ├── Middleware/
│   │   │   ├── RateLimitMiddleware.php
│   │   │   ├── LimitChecker.php
│   │   │   └── HeaderInjector.php
│   │   └── Service/
│   │       └── RateLimitService.php
│   ├── tests/
│   ├── composer.json
│   └── server.php
├── frontend-react/
│   ├── src/
│   │   ├── components/
│   │   │   ├── RateLimitMonitor.jsx
│   │   │   └── UsageDashboard.jsx
│   │   └── services/
│   │       └── rateLimitService.js
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
- `GET /api/rate-limit/status` - Get current rate limit status
- `POST /api/rate-limit/configure` - Configure rate limits
- `GET /api/rate-limit/metrics` - Get rate limit metrics
- `POST /api/rate-limit/reset` - Reset rate limit counters
- `GET /api/rate-limit/policies` - List rate limit policies
- `POST /api/rate-limit/apply` - Apply rate limit to request

## Evaluation Criteria
- Proper implementation of rate limiting algorithms
- Effective sliding window implementation
- Robust limit configuration system
- Clean middleware integration
- Comprehensive monitoring and metrics
- Comprehensive test coverage

## Resources
- [Rate Limiting](https://en.wikipedia.org/wiki/Rate_limiting)
- [Sliding Window Counter](https://medium.com/@saisandeepmopuri/system-design-rate-limiter-sliding-window-counter-approach-64e65d3e67e7)
- [API Security Best Practices](https://cheatsheetseries.owasp.org/cheatsheets/API_Security_Cheat_Sheet.html)