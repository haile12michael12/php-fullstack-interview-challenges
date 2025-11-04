# Enhanced PHP Challenge Template

This is an enhanced template for PHP fullstack challenges with modern features and best practices.

## Features

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
├── src/              # Application source code
│   ├── Controller/   # HTTP controllers
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

## Routing

Routes are defined in `config/config.php` under the `routes` key. The format is:
```php
'routes' => [
    '/api/users' => [
        'GET' => 'App\\Controller\\ApiController@getUsers',
        'POST' => 'App\\Controller\\ApiController@createUser',
    ],
]
```

## Contributing

This template is part of the PHP Fullstack Challenges project. For issues or improvements, please open a pull request.