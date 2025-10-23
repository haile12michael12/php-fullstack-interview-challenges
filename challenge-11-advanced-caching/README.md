# Challenge 11: Advanced Caching with Redis/Memcached

## Overview
This challenge focuses on implementing advanced caching strategies in PHP applications using Redis and Memcached. You'll learn how to improve application performance through various caching techniques.

## Learning Objectives
- Implement Redis caching for database query results
- Use Memcached for session storage
- Create a caching layer with cache invalidation strategies
- Implement cache warming techniques
- Apply cache-aside and write-through caching patterns
- Monitor cache performance and hit rates

## Technologies Used
- PHP 8.1+
- Redis
- Memcached
- Docker (for running cache services)
- React (frontend)

## Folder Structure
- `backend-php/`: PHP application with caching implementation
- `frontend-react/`: React frontend to interact with the caching system
- `docker-compose.yml`: Docker configuration for Redis and Memcached services

## Getting Started
1. Start the cache services with Docker: `docker-compose up -d`
2. Install PHP dependencies: `cd backend-php && composer install`
3. Start the PHP server: `php server.php`
4. Install frontend dependencies: `cd frontend-react && npm install`
5. Start the React development server: `npm run dev`