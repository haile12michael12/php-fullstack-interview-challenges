# Challenge 22: Magic Methods - Backend

This is the PHP backend for the Magic Methods challenge, demonstrating the power of PHP magic methods in building a full-stack application.

## Features

### ORM (Object-Relational Mapping)
- Entity base class with magic property access (`__get`, `__set`, `__isset`, `__unset`)
- Model class with dynamic query building (`__call`, `__callStatic`)
- QueryBuilder with fluent interface
- Relation handling

### Magic Classes
- FluentInterface for method chaining (`__call`)
- DynamicProxy for intercepting method calls (`__call`)
- MethodInterceptor for aspect-oriented programming (`__call`)

### Database
- Connection management (`__call`)
- Schema builder (`__call`)
- Migration system

## Installation

```bash
composer install
```

## Running Tests

```bash
composer test
```

## API Endpoints

### Magic Methods
- `GET /api/magic` - Magic methods overview
- `GET /api/magic/fluent` - Fluent interface demo
- `GET /api/magic/proxy` - Dynamic proxy demo
- `GET /api/magic/interceptor` - Method interceptor demo

### Entities
- `GET /api/entities` - Entities overview
- `GET /api/entities/users` - List all users
- `GET /api/entities/users/{id}` - Get user by ID
- `POST /api/entities/users` - Create new user
- `PUT /api/entities/users/{id}` - Update user
- `DELETE /api/entities/users/{id}` - Delete user

### Query Builder
- `GET /api/query` - Query builder overview
- `GET /api/query/users` - Query users with filters
- `GET /api/query/posts` - Query posts with filters
- `POST /api/query/custom` - Execute custom query

## Magic Methods Demonstrated

1. **Construction and Destruction**
   - `__construct()` - Object initialization
   - `__destruct()` - Object cleanup

2. **Property Access**
   - `__get()` - Reading inaccessible properties
   - `__set()` - Writing to inaccessible properties
   - `__isset()` - Checking if properties are set
   - `__unset()` - Unsetting properties

3. **Method Calls**
   - `__call()` - Calling inaccessible methods
   - `__callStatic()` - Calling inaccessible static methods

4. **Serialization**
   - `__sleep()` - Preparing object for serialization
   - `__wakeup()` - Restoring object from serialization

5. **Object Representation**
   - `__toString()` - Converting object to string
   - `__invoke()` - Making object callable
   - `__debugInfo()` - Custom debug information

6. **Cloning**
   - `__clone()` - Object cloning behavior