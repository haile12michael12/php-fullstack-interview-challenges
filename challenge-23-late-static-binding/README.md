# Challenge 23: Late Static Binding

## Description
In this challenge, you'll implement an advanced Active Record pattern with late static binding. Late static binding (LSB) is a feature in PHP that allows you to reference the class that was initially called at runtime, rather than the class where a method is defined. You'll create a sophisticated ORM that leverages LSB to provide flexible, inheritable database operations while maintaining type safety and proper inheritance chains.

## Learning Objectives
- Understand late static binding and its use cases
- Implement advanced Active Record patterns
- Create inheritable database operations
- Build flexible ORM architectures
- Handle static method inheritance properly
- Implement factory patterns with LSB
- Understand the difference between self:: and static::

## Requirements

### Core Features
1. **Late Static Binding Implementation**
   - Use `static::` keyword for late binding
   - Implement inheritable find methods
   - Create factory patterns with LSB
   - Handle static property inheritance
   - Implement singleton patterns with LSB

2. **Advanced Active Record Pattern**
   - Inheritable save, update, and delete operations
   - Dynamic table name resolution
   - Relationship handling with LSB
   - Query builder integration
   - Event system with proper inheritance

3. **Factory and Builder Patterns**
   - Implement factory methods using LSB
   - Create builder patterns with inheritable configurations
   - Handle polymorphic instantiation
   - Support for different database drivers
   - Fluent interface with LSB

4. **Inheritance Chain Management**
   - Proper method resolution with LSB
   - Static property inheritance
   - Type-safe method chaining
   - Polymorphic behavior
   - Abstract base class implementation

### Implementation Details
1. **Base Model with Late Static Binding**
   ```php
   abstract class Model
   {
       protected static string $table;
       protected static array $relationships = [];
       
       public static function find(int $id): ?static
       {
           // Use static:: to reference the calling class
           $query = static::query()->where('id', $id);
           return $query->first();
       }
       
       public static function all(): Collection
       {
           return static::query()->get();
       }
       
       protected static function query(): QueryBuilder
       {
           // Use static::$table to get the correct table name
           return new QueryBuilder(static::$table, static::class);
       }
   }
   ```

2. **Inheritable Factory Methods**
   ```php
   class User extends Model
   {
       protected static string $table = 'users';
       
       public static function createAdmin(array $attributes): static
       {
           $attributes['role'] = 'admin';
           return static::create($attributes);
       }
   }
   ```

## Project Structure
```
challenge-23-late-static-binding/
├── backend-php/
│   ├── src/
│   │   ├── Database/
│   │   │   ├── Model.php
│   │   │   ├── QueryBuilder.php
│   │   │   ├── Collection.php
│   │   │   └── Connection.php
│   │   ├── ORM/
│   │   │   ├── ActiveRecord.php
│   │   │   ├── Relationship.php
│   │   │   ├── Factory.php
│   │   │   └── Exception/
│   │   │       ├── ModelException.php
│   │   │       └── QueryException.php
│   │   └── Models/
│   │       ├── User.php
│   │       ├── Post.php
│   │       ├── Comment.php
│   │       └── Category.php
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
│   │   │   ├── LateStaticBindingDemo.jsx
│   │   │   ├── InheritanceChain.jsx
│   │   │   └── ModelExplorer.jsx
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
1. Navigate to the [backend-php](file:///c%3A/projects/php-fullstack-challenges/challenge-23-late-static-binding/backend-php) directory
2. Install PHP dependencies:
   ```bash
   composer install
   ```
3. Start the development server:
   ```bash
   php public/index.php
   ```

### Frontend Setup
1. Navigate to the [frontend-react](file:///c%3A/projects/php-fullstack-challenges/challenge-23-late-static-binding/frontend-react) directory
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

### Late Static Binding Demo
- **GET** `/lsb/models` - List all available models
- **GET** `/lsb/models/{model}/find/{id}` - Find record by ID
- **GET** `/lsb/models/{model}/all` - Get all records
- **POST** `/lsb/models/{model}/create` - Create new record
- **GET** `/lsb/inheritance` - View inheritance chain

## Implementation Details

### Understanding Late Static Binding
Late static binding allows you to reference the class that was initially called at runtime, rather than the class where a method is defined:

1. **Basic LSB Example**
   ```php
   class Base
   {
       public static function who()
       {
           echo "Base\n";
       }
       
       public static function test()
       {
           static::who(); // Late static binding
           self::who();   // Early binding
       }
   }
   
   class Child extends Base
   {
       public static function who()
       {
           echo "Child\n";
       }
   }
   
   Child::test();
   // Output:
   // Child
   // Base
   ```

2. **Active Record Implementation with LSB**
   ```php
   abstract class Model
   {
       protected static string $table;
       protected array $attributes = [];
       
       public static function find(int $id): ?static
       {
           // Use static:: to reference the calling class
           $result = static::query("SELECT * FROM " . static::$table . " WHERE id = ?", [$id]);
           if ($result) {
               $instance = new static();
               $instance->attributes = $result;
               return $instance;
           }
           return null;
       }
       
       public static function all(): array
       {
           $results = static::query("SELECT * FROM " . static::$table);
           $instances = [];
           foreach ($results as $result) {
               $instance = new static();
               $instance->attributes = $result;
               $instances[] = $instance;
           }
           return $instances;
       }
       
       public static function create(array $attributes): static
       {
           // Use static:: to create instance of the correct class
           $instance = new static();
           $instance->fill($attributes);
           $instance->save();
           return $instance;
       }
       
       public function save(): bool
       {
           // Implementation here
           return true;
       }
       
       protected static function query(string $sql, array $params = []): array
       {
           // Database query implementation
           return [];
       }
   }
   
   class User extends Model
   {
       protected static string $table = 'users';
   }
   
   class Post extends Model
   {
       protected static string $table = 'posts';
   }
   
   // Usage:
   $user = User::find(1); // Returns User instance
   $post = Post::find(1); // Returns Post instance
   ```

3. **Factory Pattern with LSB**
   ```php
   abstract class ModelFactory
   {
       abstract public static function getModelClass(): string;
       
       public static function create(array $attributes = []): static
       {
           $class = static::getModelClass();
           return new $class($attributes);
       }
       
       public static function createMany(int $count, array $attributes = []): array
       {
           $instances = [];
           for ($i = 0; $i < $count; $i++) {
               $instances[] = static::create($attributes);
           }
           return $instances;
       }
   }
   
   class UserFactory extends ModelFactory
   {
       public static function getModelClass(): string
       {
           return User::class;
       }
       
       public static function createAdmin(array $attributes = []): static
       {
           $attributes['role'] = 'admin';
           return static::create($attributes);
       }
   }
   
   // Usage:
   $admin = UserFactory::createAdmin(['name' => 'John']);
   ```

4. **Inheritable Query Builder**
   ```php
   class QueryBuilder
   {
       protected string $table;
       protected string $modelClass;
       protected array $conditions = [];
       
       public function __construct(string $table, string $modelClass)
       {
           $this->table = $table;
           $this->modelClass = $modelClass;
       }
       
       public function where(string $column, $value): self
       {
           $this->conditions[] = [$column, $value];
           return $this;
       }
       
       public function first(): ?object
       {
           // Execute query and return instance of the correct class
           $result = $this->execute();
           if ($result) {
               return new $this->modelClass($result);
           }
           return null;
       }
       
       public function get(): array
       {
           $results = $this->execute();
           $instances = [];
           foreach ($results as $result) {
               $instances[] = new $this->modelClass($result);
           }
           return $instances;
       }
       
       protected function execute(): array
       {
           // Database execution logic
           return [];
       }
   }
   ```

### Advanced LSB Features

1. **Static Property Inheritance**
   ```php
   abstract class BaseModel
   {
       protected static array $fillable = [];
       protected static array $hidden = [];
       protected static array $casts = [];
       
       public static function getFillable(): array
       {
           return static::$fillable;
       }
       
       public static function getHidden(): array
       {
           return static::$hidden;
       }
   }
   
   class User extends BaseModel
   {
       protected static array $fillable = ['name', 'email', 'password'];
       protected static array $hidden = ['password'];
       protected static array $casts = ['is_admin' => 'boolean'];
   }
   
   class Post extends BaseModel
   {
       protected static array $fillable = ['title', 'content', 'user_id'];
       protected static array $hidden = [];
       protected static array $casts = ['published' => 'boolean'];
   }
   ```

2. **Polymorphic Relationships**
   ```php
   abstract class Model
   {
       public static function belongsTo(string $related, string $foreignKey = null): Relationship
       {
           $foreignKey = $foreignKey ?? static::getForeignKey();
           return new BelongsToRelationship(static::class, $related, $foreignKey);
       }
       
       public static function hasMany(string $related, string $foreignKey = null): Relationship
       {
           $foreignKey = $foreignKey ?? static::getForeignKey();
           return new HasManyRelationship(static::class, $related, $foreignKey);
       }
       
       protected static function getForeignKey(): string
       {
           return strtolower(class_basename(static::class)) . '_id';
       }
   }
   ```

### Frontend Interface
The React frontend should:
1. Demonstrate late static binding concepts
2. Show inheritance chain visualization
3. Display model relationships and data
4. Provide interactive examples of LSB in action
5. Explain the difference between self:: and static::

## Evaluation Criteria
1. **Correctness** (30%)
   - Proper implementation of late static binding
   - Accurate inheritance chain management
   - Correct Active Record pattern implementation

2. **Design Quality** (25%)
   - Clean, well-structured code
   - Proper use of design patterns
   - Maintainable architecture

3. **Functionality** (20%)
   - Complete ORM feature set
   - Inheritable database operations
   - Factory and builder patterns

4. **Performance** (15%)
   - Efficient use of LSB
   - Proper caching and optimization
   - Minimal performance overhead

5. **Educational Value** (10%)
   - Clear explanations of LSB concepts
   - Practical examples and use cases
   - Interactive demonstrations

## Resources
1. [PHP Late Static Binding Documentation](https://www.php.net/manual/en/language.oop5.late-static-bindings.php)
2. [Laravel Eloquent ORM](https://laravel.com/docs/eloquent)
3. [Active Record Pattern](https://martinfowler.com/eaaCatalog/activeRecord.html)
4. [Factory Method Pattern](https://refactoring.guru/design-patterns/factory-method)
5. [PHP Best Practices for Inheritance](https://www.php.net/manual/en/language.oop5.inheritance.php)
6. [Understanding self vs static in PHP](https://www.php.net/manual/en/language.oop5.basic.php)

## Stretch Goals
1. Implement soft deletes with LSB
2. Add support for custom table names per instance
3. Create polymorphic relationship handling
4. Implement query scopes with LSB
5. Add support for database transactions
6. Create a visual inheritance chain explorer
7. Implement event system with proper inheritance