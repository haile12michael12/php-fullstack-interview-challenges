# PHP Fullstack Challenges - Backend Architecture

## Overview

This document outlines the standardized backend architecture used across all challenge projects. The architecture follows modern PHP practices with a focus on maintainability, scalability, and code reuse.

## Architecture Components

### 1. Shared Backend Library

The `shared-backend` directory contains reusable components that are used across all challenge projects:

```
shared-backend/
├── src/
│   ├── Application/    # Application bootstrapping
│   ├── Core/           # Core services (DB, Cache, Config, etc.)
│   ├── Http/           # HTTP handling (Request, Response, etc.)
│   ├── Auth/           # Authentication and authorization
│   ├── Storage/        # File storage abstractions
│   ├── Messaging/      # Message queues and event handling
│   └── Utils/          # Utility classes and helpers
```

### 2. Challenge-Specific Backend Structure

Each challenge follows this standardized structure:

```
challenge-XX-name/
├── backend-php/
│   ├── config/         # Challenge-specific configuration
│   ├── public/         # Web entry point
│   ├── src/            # Challenge-specific code
│   │   ├── Controller/ # API endpoints
│   │   ├── Model/      # Domain models
│   │   ├── Service/    # Business logic
│   │   └── Repository/ # Data access
│   ├── tests/          # Unit and integration tests
│   ├── composer.json   # Dependencies
│   └── server.php      # Server entry point
```

## Key Design Principles

1. **Dependency Injection**: Services are injected rather than instantiated directly
2. **Interface-Based Design**: Components depend on interfaces, not concrete implementations
3. **PSR Compliance**: Following PHP-FIG standards (PSR-4, PSR-7, PSR-11, PSR-15)
4. **Separation of Concerns**: Clear boundaries between layers
5. **Stateless API Design**: RESTful or gRPC APIs with proper status codes and responses

## Communication Patterns

The challenges demonstrate various communication patterns between frontend and backend:

1. **REST API** - Standard HTTP request/response
2. **gRPC** - High-performance RPC framework
3. **WebSockets** - Real-time bidirectional communication
4. **Long Polling** - Simulated real-time with delayed responses
5. **Server-Sent Events** - One-way server-to-client streaming

## Authentication & Security

1. **JWT Authentication**: Token-based authentication
2. **CORS Protection**: Proper cross-origin resource sharing
3. **Input Validation**: Request validation and sanitization
4. **Rate Limiting**: Protection against abuse
5. **Error Handling**: Consistent error responses without leaking implementation details

## Data Storage

1. **Database Abstraction**: PDO and Doctrine DBAL for database operations
2. **Migrations**: Database schema versioning
3. **File Storage**: Abstraction for local and cloud storage (S3, GCS)
4. **Caching**: Redis/Memcached integration

## Implementation Guidelines

1. Use the shared components whenever possible
2. Extend base classes rather than duplicating functionality
3. Follow PSR-12 coding standards
4. Write unit tests for business logic
5. Document public APIs using OpenAPI/Swagger

## Docker Integration

Each challenge includes Docker configuration for consistent development and deployment environments.
