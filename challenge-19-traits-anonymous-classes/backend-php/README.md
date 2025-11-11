# Challenge 19: Traits and Anonymous Classes - Backend

This backend demonstrates the power of PHP traits and anonymous classes for creating flexible and reusable code.

## Features

### Traits
- LoggerTrait: Provides logging functionality with different log levels
- CacheableTrait: Implements caching with TTL support
- ValidatableTrait: Adds data validation capabilities
- SerializableTrait: Enables object serialization to JSON, XML, and other formats
- TimestampableTrait: Manages creation and update timestamps

### Anonymous Classes
- Strategy implementations using anonymous classes
- Mock objects for testing and demonstration
- Factory patterns for creating anonymous class instances

### Interfaces
- StrategyInterface: Defines contract for strategy pattern implementations
- CacheInterface: Standardizes cache operations

### Additional Features
- Modern PHP 8.1+ with strict typing
- PSR-4 autoloading
- Dependency Injection Container (PHP-DI)
- Database abstraction with Doctrine DBAL
- JWT authentication
- Configuration management with environment variables
- Logging with Monolog
- HTTP request/response handling
- Routing system
- Caching with Redis
- File storage abstraction
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
├── routes/           # Route definitions
├── src/              # Application source code
│   ├── Traits/       # Reusable traits
│   ├── Contracts/    # Interfaces and contracts
│   ├── Factory/      # Factory classes for anonymous objects
│   ├── Service/      # Business logic services
│   ├── Http/         # HTTP utilities
│   │   ├── Controller/ # HTTP controllers
│   │   └── Middleware/ # HTTP middleware
│   ├── Infrastructure/ # Infrastructure implementations
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

## Routing

Routes are defined in `config/routes.php`. The format is:
```php
'/api/traits/logger' => [
    'POST' => 'App\\Http\\Controller\\TraitController@logMessage',
]
```

### Available API Endpoints
- `GET /` - List available endpoints
- `POST /api/traits/logger` - Log a message
- `POST /api/traits/calculate` - Perform cached calculation
- `POST /api/traits/validate` - Validate entity data
- `POST /api/traits/cache` - Cache data
- `GET /api/traits/cache/{key}` - Get cached data
- `POST /api/traits/users` - Create a user
- `GET /api/traits/stats` - Get system stats

## Contributing

This template is part of the PHP Fullstack Challenges project. For issues or improvements, please open a pull request.