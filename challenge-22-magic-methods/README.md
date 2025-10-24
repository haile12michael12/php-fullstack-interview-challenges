# Challenge 22: Magic Methods Mastery

## Description
In this challenge, you'll create an ORM-like class using PHP's magic methods (__get, __set, __call, etc.). Magic methods allow you to intercept and customize object behavior at runtime, enabling powerful features like dynamic property access, method overloading, and object serialization. You'll build a sophisticated data access layer that demonstrates the practical applications of magic methods in real-world scenarios.

## Learning Objectives
- Master PHP's magic methods and their use cases
- Implement dynamic property access and method calls
- Create fluent interfaces with __call
- Handle object serialization and debugging
- Build ORM-like functionality with magic methods
- Understand performance implications of magic methods
- Implement proper error handling with magic methods

## Requirements

### Core Features
1. **Property Access Magic Methods**
   - Implement __get for dynamic property retrieval
   - Implement __set for dynamic property assignment
   - Implement __isset for property existence checking
   - Implement __unset for property removal
   - Handle virtual properties and computed values

2. **Method Call Magic Methods**
   - Implement __call for dynamic method invocation
   - Implement __callStatic for static method interception
   - Create fluent interfaces with method chaining
   - Handle method overloading based on parameters
   - Implement proxy patterns with __call

3. **Object Lifecycle Magic Methods**
   - Implement __construct for object initialization
   - Implement __destruct for cleanup operations
   - Implement __clone for custom cloning behavior
   - Implement __sleep and __wakeup for serialization
   - Handle object state management

4. **String Representation Magic Methods**
   - Implement __toString for object string conversion
   - Implement __debugInfo for custom var_dump output
   - Create meaningful string representations
   - Handle debugging and logging scenarios

### Implementation Details
1. **ORM-like Entity Class**
   ```php
   class Entity
   {
       private array $data = [];
       private array $relations = [];
       
       public function __get(string $name) { /* ... */ }
       public function __set(string $name, $value) { /* ... */ }
       public function __isset(string $name): bool { /* ... */ }
       public function __unset(string $name): void { /* ... */ }
       public function __call(string $name, array $arguments) { /* ... */ }
       public static function __callStatic(string $name, array $arguments) { /* ... */ }
       public function __toString(): string { /* ... */ }
       public function __debugInfo(): array { /* ... */ }
   }
   ```

2. **Query Builder with Fluent Interface**
   - Use __call for method chaining
   - Implement dynamic where clauses
   - Create relationship methods dynamically
   - Handle different query types (select, insert, update, delete)

## Project Structure
```
challenge-22-magic-methods/
├── backend-php/
│   ├── src/
│   │   ├── ORM/
│   │   │   ├── Entity.php
│   │   │   ├── Model.php
│   │   │   ├── QueryBuilder.php
│   │   │   ├── Relation.php
│   │   │   └── Exception/
│   │   │       ├── ORMException.php
│   │   │       └── MagicMethodException.php
│   │   ├── Magic/
│   │   │   ├── FluentInterface.php
│   │   │   ├── DynamicProxy.php
│   │   │   └── MethodInterceptor.php
│   │   └── Database/
│   │       ├── Connection.php
│   │       ├── Schema.php
│   │       └── Migration.php
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
│   │   │   ├── MagicMethodsDemo.jsx
│   │   │   ├── EntityExplorer.jsx
│   │   │   └── QueryBuilder.jsx
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
1. Navigate to the [backend-php](file:///c%3A/projects/php-fullstack-challenges/challenge-22-magic-methods/backend-php) directory
2. Install PHP dependencies:
   ```bash
   composer install
   ```
3. Start the development server:
   ```bash
   php public/index.php
   ```

### Frontend Setup
1. Navigate to the [frontend-react](file:///c%3A/projects/php-fullstack-challenges/challenge-22-magic-methods/frontend-react) directory
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

### Magic Methods Demo
- **GET** `/magic/entity/{id}` - Get entity with dynamic properties
- **POST** `/magic/entity` - Create entity with dynamic properties
- **PUT** `/magic/entity/{id}` - Update entity with dynamic properties
- **GET** `/magic/query` - Execute dynamic query builder methods
- **GET** `/magic/debug` - View debug information for entities

## Implementation Details

### Magic Methods Overview
PHP provides several magic methods that allow you to intercept and customize object behavior:

1. **Property Access Magic Methods**
   ```php
   class Entity
   {
       private array $attributes = [];
       
       public function __get(string $name)
       {
           if (array_key_exists($name, $this->attributes)) {
               return $this->attributes[$name];
           }
           
           // Handle relationships
           if ($this->hasRelation($name)) {
               return $this->loadRelation($name);
           }
           
           throw new InvalidArgumentException("Property {$name} does not exist");
       }
       
       public function __set(string $name, $value)
       {
           $this->attributes[$name] = $value;
       }
       
       public function __isset(string $name): bool
       {
           return isset($this->attributes[$name]) || $this->hasRelation($name);
       }
       
       public function __unset(string $name): void
       {
           unset($this->attributes[$name]);
       }
   }
   ```

2. **Method Call Magic Methods**
   ```php
   class QueryBuilder
   {
       private array $conditions = [];
       
       public function __call(string $name, array $arguments)
       {
           // Handle dynamic where methods
           if (str_starts_with($name, 'where')) {
               $field = lcfirst(substr($name, 5));
               $this->conditions[] = [$field, $arguments[0]];
               return $this;
           }
           
           // Handle relationship methods
           if ($this->hasRelationship($name)) {
               return $this->with($name);
           }
           
           throw new BadMethodCallException("Method {$name} does not exist");
       }
       
       public static function __callStatic(string $name, array $arguments)
       {
           // Create new instance and call method
           $instance = new static();
           return $instance->$name(...$arguments);
       }
   }
   
   // Usage:
   // $users = QueryBuilder::whereName('John')->whereAge(25)->get();
   ```

3. **Object Lifecycle Magic Methods**
   ```php
   class Model
   {
       private bool $isDirty = false;
       
       public function __construct(array $attributes = [])
       {
           $this->fill($attributes);
           $this->initialize();
       }
       
       public function __clone()
       {
           $this->id = null;
           $this->createdAt = new DateTime();
           $this->isDirty = true;
       }
       
       public function __sleep(): array
       {
           // Only serialize specific properties
           return ['attributes', 'relations'];
       }
       
       public function __wakeup(): void
       {
           // Reconnect to database or restore state
           $this->reconnect();
       }
       
       public function __destruct()
       {
           // Save if dirty
           if ($this->isDirty) {
               $this->save();
           }
       }
   }
   ```

4. **String Representation Magic Methods**
   ```php
   class Entity
   {
       public function __toString(): string
       {
           return json_encode($this->toArray(), JSON_PRETTY_PRINT);
       }
       
       public function __debugInfo(): array
       {
           return [
               'class' => static::class,
               'attributes' => $this->attributes,
               'relations' => array_keys($this->relations),
               'isDirty' => $this->isDirty,
           ];
       }
   }
   ```

### ORM-like Implementation
The ORM implementation should include:

1. **Entity Class with Magic Methods**
   - Dynamic property access
   - Relationship handling
   - Validation and casting
   - Dirty tracking

2. **Query Builder with Fluent Interface**
   - Method chaining with __call
   - Dynamic where clauses
   - Relationship eager loading
   - Query execution

3. **Model Base Class**
   - CRUD operations
   - Event system
   - Serialization support
   - Lifecycle hooks

### Frontend Interface
The React frontend should:
1. Demonstrate magic method functionality
2. Show dynamic property access in action
3. Visualize method chaining and fluent interfaces
4. Display entity relationships and data
5. Provide interactive examples and tutorials

## Evaluation Criteria
1. **Correctness** (30%)
   - Proper implementation of all relevant magic methods
   - Accurate handling of edge cases and errors
   - Correct ORM-like functionality

2. **Performance** (25%)
   - Efficient use of magic methods
   - Proper caching and optimization
   - Minimal performance overhead

3. **Code Quality** (20%)
   - Clean, well-organized implementation
   - Proper error handling and validation
   - Comprehensive documentation

4. **Functionality** (15%)
   - Complete ORM-like feature set
   - Fluent interface implementation
   - Relationship handling

5. **Educational Value** (10%)
   - Clear explanations of magic methods
   - Practical examples and use cases
   - Interactive demonstrations

## Resources
1. [PHP Magic Methods Documentation](https://www.php.net/manual/en/language.oop5.magic.php)
2. [Laravel Eloquent ORM](https://laravel.com/docs/eloquent)
3. [Doctrine ORM](https://www.doctrine-project.org/projects/orm.html)
4. [Magic Methods Best Practices](https://www.php.net/manual/en/language.oop5.overloading.php)
5. [PHP Object Serialization](https://www.php.net/manual/en/language.oop5.serialization.php)
6. [Fluent Interface Pattern](https://martinfowler.com/bliki/FluentInterface.html)

## Stretch Goals
1. Implement custom operators for dynamic where clauses
2. Add support for nested relationships and eager loading
3. Create a migration system with magic methods
4. Implement event sourcing with __sleep/__wakeup
5. Add query caching and performance optimization
6. Create a visual query builder interface
7. Implement database schema introspection with magic methods