# Challenge 20: Reflection API - Backend

This backend demonstrates the power of PHP's Reflection API for building a dependency injection container and service locator.

## Features

### Container System
- ContainerInterface: Defines the contract for service containers
- Container: Implementation of the service container with registration and retrieval
- Service providers for modular service registration

### Reflection-Based Resolution
- ResolverInterface: Contract for class resolution
- ReflectionResolver: Uses PHP Reflection API to automatically resolve class dependencies
- Automatic parameter injection based on type hints

### Service Factory
- FactoryInterface: Contract for service creation
- ServiceFactory: Creates instances of classes with dependency resolution
- Circular dependency detection

### Exception Handling
- ContainerException: Base exception for container errors
- NotFoundException: Thrown when a service is not found
- CircularDependencyException: Thrown when circular dependencies are detected

### Service Implementations
- Logger implementations (FileLogger, DatabaseLogger)
- Mailer implementation (SmtpMailer)
- Service providers for modular registration

### Additional Features
- Modern PHP 8.1+ with strict typing
- PSR-4 autoloading
- Configuration management with environment variables
- Testing framework (PHPUnit)
- Static analysis (PHPStan)
- Code quality tools (PHP_CodeSniffer, PHPMD)

## Requirements

- PHP 8.1 or higher
- Composer
- MySQL/PostgreSQL/SQLite (for database challenges)
- Redis (for caching challenges)

## Setup

1. Clone the challenge directory
2. Navigate to the `backend-php` directory
3. Install dependencies:
   ```bash
   composer install
   ```
4. Copy the `.env.example` file to `.env` and configure your settings:
   ```bash
   cp .env.example .env
   ```
5. Start the development server:
   ```bash
   php -S localhost:8000 -t public
   ```

## Directory Structure

```
backend-php/
├── config/           # Configuration files
├── public/           # Web root
├── src/              # Application source code
│   ├── Container/    # Container system
│   │   ├── Contracts/ # Container interfaces
│   │   ├── Core/      # Container core implementations
│   │   ├── Exception/ # Container exceptions
│   │   └── Services/  # Service implementations
│   ├── Http/         # HTTP utilities
│   └── Application.php # Main application class
├── tests/            # Test files
├── vendor/           # Composer dependencies
├── .env.example      # Environment variables template
├── composer.json     # Composer configuration
└── README.md         # This file
```

## Available Scripts

- `composer test` - Run PHPUnit tests
- `composer phpstan` - Run static analysis
- `composer cs` - Check code style
- `composer cs-fix` - Fix code style issues
- `composer phpmd` - Run PHP Mess Detector

## Configuration

The application uses environment variables for configuration. Copy `.env.example` to `.env` and modify the values as needed.

## API Endpoints

- `GET /api.php` - Initialize the reflection API and demonstrate service resolution

## Contributing

This template is part of the PHP Fullstack Challenges project. For issues or improvements, please open a pull request.