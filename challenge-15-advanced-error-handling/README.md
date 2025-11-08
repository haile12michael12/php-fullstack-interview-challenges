# Challenge 15: Advanced Error Handling

A comprehensive full-stack application demonstrating advanced error handling patterns in PHP (backend) and React (frontend).

## Overview

This project implements a robust error handling system with multiple recovery strategies including retry mechanisms, circuit breakers, and fallback strategies. It showcases best practices for error handling in both backend and frontend applications.

## Features

### Backend (PHP)
- Custom exception hierarchy
- Global error middleware
- Structured logging with correlation ID tracking
- Retry mechanisms with exponential backoff
- Circuit breaker pattern implementation
- Fallback strategies for graceful degradation
- Health check endpoints
- Comprehensive testing suite

### Frontend (React)
- Error boundaries for component-level error handling
- Centralized error service
- Retry handler with exponential backoff
- Notification system
- Fallback views for degraded experiences
- Context-based error state management
- Structured logging utilities

## Project Structure

```
challenge-15-advanced-error-handling/
├── backend-php/          # PHP backend application
│   ├── app/              # Application source code
│   ├── config/           # Configuration files
│   ├── public/           # Public assets and entry point
│   ├── storage/          # Logs, cache, and storage
│   ├── tests/            # Unit and integration tests
│   ├── composer.json     # PHP dependencies
│   └── Dockerfile        # Backend Docker configuration
│
├── frontend-react/       # React frontend application
│   ├── src/              # Source code
│   ├── public/           # Public assets
│   ├── package.json      # Node.js dependencies
│   └── Dockerfile        # Frontend Docker configuration
│
├── docs/                 # Documentation
├── .github/              # GitHub workflows and issue templates
├── docker-compose.yml    # Docker Compose configuration
└── README.md             # This file
```

## Getting Started

### Prerequisites

- Docker and Docker Compose
- PHP 8.1+
- Node.js 16+
- Composer
- npm or yarn

### Quick Start with Docker

```bash
# Clone the repository
git clone <repository-url>
cd challenge-15-advanced-error-handling

# Start all services
docker-compose up -d

# Access the applications
# Frontend: http://localhost:3000
# Backend API: http://localhost:8000/api
# PHPMyAdmin: http://localhost:8080
```

### Manual Setup

#### Backend (PHP)

```bash
cd backend-php

# Install PHP dependencies
composer install

# Copy and configure environment file
cp .env.example .env

# Start the development server
php -S localhost:8000 -t public
```

#### Frontend (React)

```bash
cd frontend-react

# Install Node.js dependencies
npm install

# Start the development server
npm run dev
```

## Documentation

Detailed documentation is available in the [docs](./docs) directory:

- [Design Documentation](./docs/DESIGN.md) - System architecture and design patterns
- [Error Handling Guide](./docs/ERROR_HANDLING_GUIDE.md) - Comprehensive error handling implementation
- [Logging Guide](./docs/LOGGING_GUIDE.md) - Structured logging implementation
- [Recovery Strategies](./docs/RECOVERY_STRATEGIES.md) - Retry, circuit breaker, and fallback implementations
- [Frontend Error Flow](./docs/FRONTEND_ERROR_FLOW.md) - Frontend error handling flow
- [API Reference](./docs/API_REFERENCE.md) - Backend API documentation

## Error Handling Patterns

### Backend Patterns

1. **Exception Hierarchy** - Custom exception types for different error scenarios
2. **Global Error Middleware** - Centralized error handling for all requests
3. **Structured Logging** - Context-rich logging with correlation IDs
4. **Retry Mechanism** - Exponential backoff for transient failures
5. **Circuit Breaker** - Prevent cascading failures
6. **Fallback Strategy** - Graceful degradation

### Frontend Patterns

1. **Error Boundaries** - React component error handling
2. **Error Service** - Centralized error processing
3. **Retry Handler** - Client-side retry mechanisms
4. **Notification System** - User feedback for errors
5. **Fallback Views** - Degraded experiences for failures
6. **Context Providers** - Global error state management

## Testing

### Backend Testing

```bash
cd backend-php

# Run unit tests
vendor/bin/phpunit

# Run specific test suite
vendor/bin/phpunit tests/Unit/ExceptionHandlerTest.php
```

### Frontend Testing

```bash
cd frontend-react

# Run tests
npm test

# Run tests with coverage
npm run test:coverage
```

## API Endpoints

The backend provides several endpoints for testing error handling:

- `/api/health` - Health check endpoints
- `/api/error-test/*` - Various error scenarios
- `/api/recovery-test/*` - Recovery mechanism testing
- `/api/users`, `/api/products`, `/api/orders` - Standard CRUD operations

Full API documentation is available in [API_REFERENCE.md](./docs/API_REFERENCE.md).

## Docker Configuration

The project includes Docker configurations for easy deployment:

- [Backend Dockerfile](./backend-php/Dockerfile)
- [Frontend Dockerfile](./frontend-react/Dockerfile)
- [Docker Compose](./docker-compose.yml)

## GitHub Workflows

Automated workflows are configured in [.github/workflows](./.github/workflows):

- Backend tests
- Frontend tests
- Build and lint
- Docker image publishing

## Contributing

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## License

This project is licensed under the MIT License - see the [LICENSE](./LICENSE) file for details.

## Troubleshooting

### Common Issues

1. **Database connection failures** - Check database credentials in .env file
2. **CORS errors** - Verify allowed origins in backend configuration
3. **Docker volume permissions** - Ensure proper file permissions for mounted volumes
4. **Port conflicts** - Change port mappings in docker-compose.yml if needed

### Debugging Tips

1. Check container logs: `docker-compose logs <service>`
2. Verify service health: `docker-compose ps`
3. Access containers: `docker-compose exec <service> sh`
4. Review application logs in `storage/logs/` directory

## Support

For support, please open an issue on GitHub or contact the maintainers.