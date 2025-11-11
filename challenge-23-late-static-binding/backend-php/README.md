# Challenge 23: Late Static Binding - Backend

This is the PHP backend for the Late Static Binding challenge, demonstrating the power of late static binding in building a full-stack application.

## Features

### Database Layer
- Connection management with late static binding
- QueryBuilder with fluent interface
- Model base class with late static binding
- Collection class for handling model collections

### ORM (Object-Relational Mapping)
- ActiveRecord pattern implementation
- Relationship handling (hasOne, hasMany, belongsTo, belongsToMany)
- Factory pattern for model creation
- Event system for model lifecycle events

### Models
- User model with posts and comments relationships
- Post model with user, comments, and categories relationships
- Comment model with user and post relationships
- Category model with posts relationship

### HTTP Layer
- Controllers with JSON response handling
- Middleware for CORS, authentication, and JSON responses
- Routing system

### Utilities
- Logger class with late static binding
- Config class with late static binding
- Env class for environment variable handling
- Helper functions

## Installation

```bash
composer install
```

## Running Tests

```bash
composer test
```

## API Endpoints

### Late Static Binding
- `GET /api/lsb` - LSB overview
- `GET /api/lsb/users` - List all users
- `GET /api/lsb/users/{id}` - Get user by ID
- `POST /api/lsb/users` - Create a new user
- `GET /api/lsb/posts` - List all posts
- `GET /api/lsb/posts/{id}` - Get post by ID
- `POST /api/lsb/posts` - Create a new post

### Model Inheritance
- `GET /api/models` - Models overview
- `GET /api/models/users` - List all users with posts
- `GET /api/models/posts` - List all posts with relationships
- `GET /api/models/categories` - List all categories with posts

### Inheritance Concepts
- `GET /api/inheritance` - Inheritance overview
- `GET /api/inheritance/concepts` - List all inheritance concepts
- `GET /api/inheritance/examples` - Show LSB examples
- `POST /api/inheritance/factory` - Create models using factory

## Late Static Binding Concepts Demonstrated

1. **Late Static Binding**
   - `static::` keyword vs `self::` keyword
   - Static method inheritance
   - Factory pattern with late static binding

2. **Active Record Pattern**
   - Model inheritance
   - Query building with late static binding
   - Relationship handling

3. **Factory Pattern**
   - Model creation with late static binding
   - State management

4. **Event System**
   - Event dispatching with late static binding
   - Listener management