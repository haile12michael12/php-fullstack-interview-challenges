# Challenge 58: Dependency Injection Container

## Description
In this challenge, you'll build a powerful dependency injection container that automatically resolves class dependencies. This is a fundamental component of modern PHP frameworks and will help you understand how services are managed and injected.

## Learning Objectives
- Understand dependency injection principles
- Implement automatic dependency resolution
- Create service container with bindings
- Handle circular dependencies
- Support singleton and factory patterns
- Implement interface binding

## Requirements
Create a dependency injection container with the following features:

1. **Automatic Resolution**:
   - Automatically resolve class dependencies through reflection
   - Handle primitive type parameters with default values
   - Support constructor injection
   - Resolve nested dependencies

2. **Binding System**:
   - Bind interfaces to concrete implementations
   - Bind to closures for factory patterns
   - Bind to specific instances (singletons)
   - Bind to primitive values

3. **Resolution Strategies**:
   - Resolve concrete classes automatically
   - Resolve through interface bindings
   - Resolve through custom bindings
   - Handle unresolvable dependencies gracefully

4. **Lifecycle Management**:
   - Singleton instances that persist across requests
   - Factory instances that create new objects each time
   - Shared instances within a request
   - Proper cleanup of resources

5. **Advanced Features**:
   - Contextual binding based on parent class
   - Tagged services for grouping
   - Service providers for modular organization
   - Extension points for modifying existing bindings

## Features to Implement
- [ ] Automatic dependency resolution through reflection
- [ ] Interface to concrete class binding
- [ ] Singleton and factory instance management
- [ ] Closure binding for complex instantiation
- [ ] Primitive parameter binding
- [ ] Contextual binding support
- [ ] Service tagging and retrieval
- [ ] Service providers for modular organization
- [ ] Circular dependency detection
- [ ] Comprehensive error handling

## Project Structure
```
backend-php/
├── src/
│   ├── Container/
│   │   ├── Container.php
│   │   ├── ContainerInterface.php
│   │   ├── BindingResolutionException.php
│   │   └── EntryNotFoundException.php
│   ├── Providers/
│   │   ├── ServiceProvider.php
│   │   └── ServiceManager.php
│   ├── Services/
│   │   ├── DatabaseService.php
│   │   ├── LoggerService.php
│   │   └── CacheService.php
│   └── Exceptions/
├── public/
│   └── index.php
├── config/
│   └── services.php
└── vendor/

frontend-react/
├── src/
│   ├── components/
│   ├── App.jsx
│   └── main.jsx
├── public/
└── package.json
```

## Setup Instructions
1. Navigate to the `backend-php` directory
2. Run `composer install` to install dependencies
3. Configure your web server to point to the `public` directory
4. Start the development server with `php -S localhost:8000 -t public`
5. Navigate to the `frontend-react` directory
6. Run `npm install` to install dependencies
7. Start the development server with `npm run dev`

## API Endpoints
- `GET /services` - List all registered services
- `POST /services/resolve` - Resolve a service by name
- `GET /services/{name}` - Get service details
- `POST /services/bind` - Bind a new service

## Evaluation Criteria
- [ ] Container correctly resolves dependencies through reflection
- [ ] Interface bindings work properly
- [ ] Singleton instances are reused
- [ ] Factory instances create new objects
- [ ] Circular dependency detection works
- [ ] Error handling is comprehensive
- [ ] Contextual binding is implemented
- [ ] Code is well-organized and documented
- [ ] Tests cover all container functionality

## Resources
- [Dependency Injection](https://en.wikipedia.org/wiki/Dependency_injection)
- [Inversion of Control](https://martinfowler.com/bliki/InversionOfControl.html)
- [PHP-DI Documentation](https://php-di.org/)
- [Laravel Service Container](https://laravel.com/docs/container)