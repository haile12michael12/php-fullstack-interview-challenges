# Challenge 20: Reflection API

This challenge demonstrates the power of PHP's Reflection API for building a dependency injection container and service locator.

## Project Structure

```
challenge-20-reflection-api/
├── backend-php/
│   ├── src/
│   │   ├── Container/
│   │   │   ├── Contracts/
│   │   │   │   ├── ContainerInterface.php
│   │   │   │   ├── ResolverInterface.php
│   │   │   │   └── FactoryInterface.php
│   │   │   ├── Core/
│   │   │   │   ├── Container.php
│   │   │   │   ├── ReflectionResolver.php
│   │   │   │   ├── ServiceFactory.php
│   │   │   │   └── ServiceRegistry.php
│   │   │   ├── Exception/
│   │   │   │   ├── ContainerException.php
│   │   │   │   ├── NotFoundException.php
│   │   │   │   └── CircularDependencyException.php
│   │   │   └── Services/
│   │   │       ├── Interfaces/
│   │   │       │   ├── LoggerInterface.php
│   │   │       │   └── MailerInterface.php
│   │   │       ├── Implementations/
│   │   │       │   ├── FileLogger.php
│   │   │       │   ├── DatabaseLogger.php
│   │   │       │   └── SmtpMailer.php
│   │   │       └── Providers/
│   │   │           ├── LoggingServiceProvider.php
│   │   │           ├── MailServiceProvider.php
│   │   │           └── DatabaseServiceProvider.php
│   ├── config/
│   │   ├── services.php
│   │   └── parameters.php
│   ├── public/
│   │   ├── index.php
│   │   └── api.php
│   ├── tests/
│   │   ├── ContainerTest.php
│   │   ├── ReflectionResolverTest.php
│   │   └── ServiceFactoryTest.php
│   ├── composer.json
│   ├── Dockerfile
│   └── README.md
├── frontend-react/
│   ├── src/
│   │   ├── components/
│   │   │   ├── ServiceContainer.jsx
│   │   │   ├── DependencyGraph.jsx
│   │   │   └── ServiceInspector.jsx
│   │   └── App.jsx
│   ├── package.json
│   ├── vite.config.js
│   ├── Dockerfile
│   └── README.md
├── docker-compose.yml
└── README.md
```

## Features

### Backend (PHP)

1. **Container System**
   - `ContainerInterface`: Defines the contract for service containers
   - `Container`: Implementation of the service container with registration and retrieval
   - Service providers for modular service registration

2. **Reflection-Based Resolution**
   - `ResolverInterface`: Contract for class resolution
   - `ReflectionResolver`: Uses PHP Reflection API to automatically resolve class dependencies
   - Automatic parameter injection based on type hints

3. **Service Factory**
   - `FactoryInterface`: Contract for service creation
   - `ServiceFactory`: Creates instances of classes with dependency resolution
   - Circular dependency detection

4. **Exception Handling**
   - `ContainerException`: Base exception for container errors
   - `NotFoundException`: Thrown when a service is not found
   - `CircularDependencyException`: Thrown when circular dependencies are detected

5. **Service Implementations**
   - Logger implementations (FileLogger, DatabaseLogger)
   - Mailer implementation (SmtpMailer)
   - Service providers for modular registration

### Frontend (React)

1. **Components**
   - `ServiceContainer`: Displays registered services
   - `DependencyGraph`: Visualizes service dependencies
   - `ServiceInspector`: Inspects service details using reflection

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
php -S localhost:8000 -t public
```

### Frontend
```bash
cd frontend-react
npm run dev
```

## API Endpoints

- `GET /api.php` - Initialize the reflection API and demonstrate service resolution

## Testing

### Backend
```bash
cd backend-php
./vendor/bin/phpunit
```

## Key Concepts Demonstrated

1. **Reflection API**: Automatic class analysis and dependency resolution
2. **Dependency Injection**: Automatic injection of dependencies using type hints
3. **Service Container**: Centralized service management and retrieval
4. **Service Providers**: Modular service registration
5. **Factory Pattern**: Object creation with complex initialization
6. **Circular Dependency Detection**: Prevention of infinite loops in dependency resolution