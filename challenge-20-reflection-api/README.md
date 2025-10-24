# Challenge 20: Reflection API

## Description
In this challenge, you'll build a dynamic dependency injection container using PHP's Reflection API. The Reflection API allows you to inspect and manipulate classes, methods, properties, and functions at runtime. You'll create a powerful DI container that can automatically resolve dependencies by analyzing constructor parameters and injecting appropriate services.

## Learning Objectives
- Understand PHP's Reflection API capabilities
- Implement dynamic class instantiation
- Build automatic dependency resolution
- Create a service container with auto-wiring
- Handle complex dependency graphs
- Implement caching for reflection metadata
- Understand type hints and parameter resolution

## Requirements

### Core Features
1. **Reflection-Based Container**
   - Automatically resolve class dependencies using ReflectionClass
   - Inspect constructor parameters and their types
   - Instantiate classes with resolved dependencies
   - Handle scalar parameter injection
   - Support interface to implementation mapping

2. **Service Registration**
   - Register services with explicit definitions
   - Register services with automatic resolution
   - Support singleton and prototype instantiation
   - Allow parameter overrides during resolution
   - Implement service aliases

3. **Dependency Resolution**
   - Resolve nested dependencies automatically
   - Handle circular dependency detection
   - Support optional parameters with defaults
   - Implement lazy loading for expensive services
   - Cache reflection metadata for performance

4. **Advanced Features**
   - Support method injection in addition to constructor injection
   - Implement callable resolution for closures
   - Add tagging system for grouping services
   - Create factory patterns with reflection
   - Handle union types and mixed parameters

### Implementation Details
1. **Container Interface**
   ```php
   interface ContainerInterface
   {
       public function get(string $id);
       public function has(string $id): bool;
       public function set(string $id, $concrete = null): void;
       public function resolve(string $class, array $parameters = []);
   }
   ```

2. **Reflection Resolver**
   - Use `ReflectionClass` to analyze class structure
   - Use `ReflectionParameter` to inspect constructor parameters
   - Handle type hints for dependency resolution
   - Manage parameter defaults and optionality

## Project Structure
```
challenge-20-reflection-api/
├── backend-php/
│   ├── src/
│   │   ├── Container/
│   │   │   ├── Container.php
│   │   │   ├── ContainerInterface.php
│   │   │   ├── ReflectionResolver.php
│   │   │   └── Exception/
│   │   │       ├── ContainerException.php
│   │   │       └── NotFoundException.php
│   │   └── Services/
│   │       ├── DatabaseConnection.php
│   │       ├── Logger.php
│   │       ├── Cache.php
│   │       └── Mailer.php
│   ├── config/
│   ├── public/
│   │   └── index.php
│   ├── tests/
│   ├── composer.json
│   ├── Dockerfile
│   └── README.md
├── frontend-react/
│   ├── src/
│   │   ├── components/
│   │   │   ├── ServiceContainer.jsx
│   │   │   └── DependencyGraph.jsx
│   │   └── App.jsx
│   ├── package.json
│   ├── vite.config.js
│   ├── Dockerfile
│   └── README.md
├── docker-compose.yml
└── README.md
```

## Setup Instructions

### Prerequisites
- PHP 8.1+
- Composer
- Node.js 16+
- npm or yarn
- Docker (optional, for containerized deployment)

### Backend Setup
1. Navigate to the [backend-php](file:///c%3A/projects/php-fullstack-challenges/challenge-20-reflection-api/backend-php) directory
2. Install PHP dependencies:
   ```bash
   composer install
   ```
3. Start the development server:
   ```bash
   php public/index.php
   ```

### Frontend Setup
1. Navigate to the [frontend-react](file:///c%3A/projects/php-fullstack-challenges/challenge-20-reflection-api/frontend-react) directory
2. Install JavaScript dependencies:
   ```bash
   npm install
   ```
3. Start the development server:
   ```bash
   npm run dev
   ```

### Docker Setup
1. From the challenge root directory, run:
   ```bash
   docker-compose up -d
   ```
2. Access the application at `http://localhost:3000`

## API Endpoints

### Container Management
- **GET** `/container/services` - List registered services
- **POST** `/container/register` - Register a new service
- **GET** `/container/resolve/{service}` - Resolve a service
- **DELETE** `/container/clear` - Clear container cache

## Implementation Details

### Reflection API Usage
The core of this challenge is using PHP's Reflection API to analyze classes:

1. **Class Analysis**
   ```php
   $reflection = new ReflectionClass($className);
   $constructor = $reflection->getConstructor();
   $parameters = $constructor->getParameters();
   ```

2. **Parameter Resolution**
   ```php
   foreach ($parameters as $parameter) {
       $type = $parameter->getType();
       $name = $parameter->getName();
       $isOptional = $parameter->isOptional();
   }
   ```

3. **Instance Creation**
   ```php
   $instance = $reflection->newInstanceArgs($resolvedDependencies);
   ```

### Container Implementation
The container should:
1. Maintain a registry of services
2. Use reflection to resolve dependencies
3. Handle circular dependency detection
4. Support both singleton and prototype patterns
5. Cache reflection metadata for performance

### Frontend Interface
The React frontend should:
1. Visualize the service container
2. Show dependency graphs
3. Allow service registration and resolution
4. Display performance metrics
5. Provide educational content about reflection

## Evaluation Criteria
1. **Correctness** (30%)
   - Proper use of Reflection API
   - Accurate dependency resolution
   - Correct handling of edge cases

2. **Robustness** (25%)
   - Error handling and validation
   - Circular dependency detection
   - Memory management and caching

3. **Performance** (20%)
   - Efficient reflection usage
   - Proper caching implementation
   - Scalable architecture

4. **Code Quality** (15%)
   - Clean, well-organized code
   - Proper documentation and comments
   - Following PHP best practices

5. **User Experience** (10%)
   - Intuitive frontend interface
   - Clear visualization of concepts
   - Helpful educational content

## Resources
1. [PHP Reflection Documentation](https://www.php.net/manual/en/book.reflection.php)
2. [Dependency Injection Container Pattern](https://martinfowler.com/articles/injection.html)
3. [PHP-DI Documentation](http://php-di.org/)
4. [Symfony DI Component](https://symfony.com/doc/current/components/dependency_injection.html)
5. [Understanding Reflection in PHP](https://www.sitepoint.com/reflection-php/)
6. [PSR-11 Container Interface](https://www.php-fig.org/psr/psr-11/)

## Stretch Goals
1. Implement autowiring for method calls
2. Add support for union types
3. Create a configuration-based service registry
4. Implement service providers pattern
5. Add profiling and debugging tools
6. Create a visual dependency graph renderer