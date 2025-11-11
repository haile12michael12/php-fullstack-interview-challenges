# Challenge 19: Traits and Anonymous Classes

This challenge demonstrates the power of PHP traits and anonymous classes for creating flexible and reusable code.

## Project Structure

```
challenge-19-traits-anonymous-classes/
├── backend-php/
│   ├── config/
│   │   ├── routes.php
│   │   └── services.php
│   ├── public/
│   │   └── index.php
│   ├── src/
│   │   ├── Traits/
│   │   │   ├── LoggerTrait.php
│   │   │   ├── CacheableTrait.php
│   │   │   ├── ValidatableTrait.php
│   │   │   ├── SerializableTrait.php
│   │   │   └── TimestampableTrait.php
│   │   ├── Contracts/
│   │   │   ├── StrategyInterface.php
│   │   │   └── CacheInterface.php
│   │   ├── Factory/
│   │   │   ├── AnonymousClassFactory.php
│   │   │   └── MockFactory.php
│   │   ├── Service/
│   │   │   └── BusinessLogicService.php
│   │   ├── Http/
│   │   │   ├── Controller/
│   │   │   │   └── TraitController.php
│   │   │   └── Middleware/
│   │   └── Infrastructure/
│   │       ├── PsrLoggerAdapter.php
│   │       └── ArrayCache.php
│   ├── tests/
│   ├── .env.example
│   ├── composer.json
│   ├── Dockerfile
│   └── phpunit.xml
├── frontend-react/
│   ├── src/
│   │   ├── components/
│   │   │   ├── TraitDemo.jsx
│   │   │   └── AnonymousClassDemo.jsx
│   │   ├── pages/
│   │   │   └── Playground.jsx
│   │   └── services/
│   │       └── oopService.js
│   ├── tests/
│   ├── storybook/
│   ├── package.json
│   └── vite.config.js
├── .github/
│   └── workflows/
│       └── ci.yml
├── docker-compose.yml
├── Makefile
└── README.md
```

## Features

### Backend (PHP)

1. **Traits**
   - LoggerTrait: Provides logging functionality with different log levels
   - CacheableTrait: Implements caching with TTL support
   - ValidatableTrait: Adds data validation capabilities
   - SerializableTrait: Enables object serialization to JSON, XML, and other formats
   - TimestampableTrait: Manages creation and update timestamps

2. **Anonymous Classes**
   - Strategy implementations using anonymous classes
   - Mock objects for testing and demonstration
   - Factory patterns for creating anonymous class instances

3. **Interfaces**
   - StrategyInterface: Defines contract for strategy pattern implementations
   - CacheInterface: Standardizes cache operations

4. **Factories**
   - AnonymousClassFactory: Creates anonymous class instances with traits
   - MockFactory: Generates mock objects for testing

### Frontend (React)

1. **Components**
   - TraitDemo: Interactive demonstration of trait functionality
   - AnonymousClassDemo: Showcase of anonymous class capabilities

2. **Pages**
   - Playground: Main page for experimenting with traits and anonymous classes

3. **Services**
   - oopService: API communication layer for backend operations

## Installation

### Backend
```bash
cd backend-php
composer install
cp .env.example .env
# Update .env with your configuration
```

### Frontend
```bash
cd frontend-react
npm install
```

## Running the Application

### Backend
```bash
cd backend-php
php server.php
```

### Frontend
```bash
cd frontend-react
npm run dev
```

## API Endpoints

- `GET /` - List available endpoints
- `POST /api/traits/logger` - Log a message
- `POST /api/traits/calculate` - Perform cached calculation
- `POST /api/traits/validate` - Validate entity data
- `POST /api/traits/cache` - Cache data
- `GET /api/traits/cache/{key}` - Get cached data
- `POST /api/traits/users` - Create a user
- `GET /api/traits/stats` - Get system stats

## Testing

### Backend
```bash
cd backend-php
./vendor/bin/phpunit
```

## Key Concepts Demonstrated

1. **Traits**: Reusable code fragments that can be composed into classes
2. **Anonymous Classes**: Classes defined without a name, useful for one-off implementations
3. **Factory Pattern**: Centralized object creation with complex initialization
4. **Strategy Pattern**: Algorithm encapsulation with interchangeable implementations
5. **Composition over Inheritance**: Building functionality through trait composition