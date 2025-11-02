# Challenge 11: Advanced Caching with Redis/Memcached

## Description
This challenge focuses on implementing advanced caching strategies in PHP applications using Redis and Memcached. You'll learn how to improve application performance through various caching techniques, implement cache invalidation strategies, and create a robust caching layer that can handle complex data patterns and high-concurrency scenarios.

## Learning Objectives
- Implement Redis caching for database query results
- Use Memcached for session storage
- Create a caching layer with cache invalidation strategies
- Implement cache warming techniques
- Apply cache-aside and write-through caching patterns
- Monitor cache performance and hit rates
- Handle cache consistency and distributed caching
- Implement fallback mechanisms for cache failures

## Requirements
- PHP 8.1+
- Composer
- Node.js 16+
- Redis server
- Memcached server
- Docker (for running cache services)
- Understanding of caching patterns and strategies

## Features to Implement
1. **Multi-Level Caching**
   - In-memory caching with APCu
   - Distributed caching with Redis
   - Session storage with Memcached
   - Cache hierarchy and fallback mechanisms
   
2. **Cache Invalidation Strategies**
   - Time-based expiration (TTL)
   - Event-driven invalidation
   - Cache tagging and selective clearing
   - Cache versioning and updates
   
3. **Performance Optimization**
   - Cache warming for frequently accessed data
   - Lazy loading and cache-on-read patterns
   - Write-through and write-behind caching
   - Cache sharding for large datasets
   
4. **Monitoring and Analytics**
   - Cache hit/miss ratio tracking
   - Performance metrics and logging
   - Memory usage monitoring
   - Cache size and eviction analysis

## Project Structure
```
challenge-11-advanced-caching/
├── backend-php/
│   ├── src/
│   │   ├── Cache/
│   │   │   ├── CacheManager.php
│   │   │   ├── RedisAdapter.php
│   │   │   ├── MemcachedAdapter.php
│   │   │   └── CacheInterface.php
│   │   ├── Service/
│   │   │   ├── UserService.php
│   │   │   ├── ProductService.php
│   │   │   └── CacheWarmService.php
│   │   └── Exception/
│   │       ├── CacheException.php
│   │       └── CacheMissException.php
│   ├── public/
│   │   └── index.php
│   ├── config/
│   ├── tests/
│   ├── composer.json
│   └── Dockerfile
├── frontend-react/
│   ├── src/
│   │   ├── components/
│   │   │   ├── CacheDashboard.jsx
│   │   │   ├── PerformanceChart.jsx
│   │   │   └── CacheStats.jsx
│   │   ├── services/
│   │   │   └── cacheService.js
│   │   ├── App.jsx
│   │   └── main.jsx
│   ├── package.json
│   ├── vite.config.js
│   └── Dockerfile
├── docker-compose.yml
└── README.md
```

## Setup Instructions

### Prerequisites
- PHP 8.1+
- Composer
- Node.js 16+
- Docker and Docker Compose

### Backend Setup
1. Start the cache services with Docker:
   ```bash
   docker-compose up -d
   ```
2. Navigate to the `backend-php` directory
3. Run `composer install` to install dependencies
4. Start the PHP server:
   ```bash
   php server.php
   ```

### Frontend Setup
1. Navigate to the `frontend-react` directory
2. Run `npm install` to install frontend dependencies
3. Run `npm run dev` to start the React development server

### Accessing the Application
- Backend API: `http://localhost:8080`
- Frontend Dashboard: `http://localhost:3000`

## API Endpoints
- `GET /api/cache/stats` - Get cache performance statistics
- `POST /api/cache/warm` - Trigger cache warming process
- `DELETE /api/cache/flush` - Clear all cache entries
- `GET /api/users/{id}` - Get user with caching
- `GET /api/products/{id}` - Get product with caching
- `POST /api/cache/invalidate` - Invalidate specific cache entries

## Evaluation Criteria
- [ ] Effective implementation of multiple caching strategies
- [ ] Proper cache invalidation and consistency mechanisms
- [ ] Performance optimization and monitoring
- [ ] Robust error handling and fallback mechanisms
- [ ] Code quality and documentation
- [ ] Comprehensive test coverage
- [ ] Docker configuration for cache services

## Resources
- [Redis Documentation](https://redis.io/documentation)
- [Memcached Documentation](https://memcached.org/)
- [PHP Redis Extension](https://github.com/phpredis/phpredis)
- [Caching Best Practices](https://docs.microsoft.com/en-us/azure/architecture/best-practices/caching)
- [Cache-Aside Pattern](https://docs.microsoft.com/en-us/azure/architecture/patterns/cache-aside)