# Challenge 57: MVC Framework

## Description
In this challenge, you'll build a lightweight Model-View-Controller (MVC) framework from scratch. This will help you understand how popular PHP frameworks like Laravel, Symfony, and CodeIgniter work under the hood.

## Learning Objectives
- Understand the MVC architectural pattern
- Implement routing mechanisms
- Create a request/response cycle
- Build controller and view rendering systems
- Implement middleware support
- Handle dependency injection

## Requirements
Create a lightweight MVC framework with the following features:

1. **Routing System**:
   - Define routes with HTTP methods (GET, POST, PUT, DELETE)
   - Support route parameters with validation
   - Named routes and route groups
   - Route middleware attachment

2. **Request/Response Handling**:
   - HTTP request parsing
   - Response object with status codes and headers
   - JSON and HTML response formatting
   - Session and cookie management

3. **Controller System**:
   - Base controller class
   - Method injection for dependencies
   - Automatic parameter binding from route parameters
   - Before/after filters

4. **View System**:
   - Template engine with variable substitution
   - Layout and partial views
   - Template inheritance
   - Escaping for security

5. **Middleware Pipeline**:
   - Middleware interface
   - Request/response transformation
   - Global and route-specific middleware
   - Error handling middleware

6. **Dependency Injection Container**:
   - Service container with auto-wiring
   - Singleton and factory bindings
   - Interface binding
   - Container-aware controllers

## Features to Implement
- [ ] Router with GET, POST, PUT, DELETE methods
- [ ] Route parameters with constraints
- [ ] Controller base class
- [ ] View rendering system
- [ ] Middleware pipeline
- [ ] Dependency injection container
- [ ] Request/Response objects
- [ ] Session and cookie handling
- [ ] Error and exception handling
- [ ] Basic ORM integration example

## Project Structure
```
backend-php/
├── src/
│   ├── Framework/
│   │   ├── Router.php
│   │   ├── Request.php
│   │   ├── Response.php
│   │   ├── Controller.php
│   │   ├── View.php
│   │   ├── Middleware/
│   │   │   ├── MiddlewareInterface.php
│   │   │   └── Pipeline.php
│   │   └── Container/
│   │       ├── Container.php
│   │       └── ContainerInterface.php
│   ├── Controllers/
│   │   └── HomeController.php
│   ├── Models/
│   └── Views/
│       ├── layouts/
│       └── home.php
├── public/
│   └── index.php
├── config/
│   └── routes.php
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
- `GET /` - Home page
- `GET /users` - List users
- `GET /users/{id}` - Get user by ID
- `POST /users` - Create new user
- `PUT /users/{id}` - Update user
- `DELETE /users/{id}` - Delete user

## Evaluation Criteria
- [ ] Framework follows MVC pattern correctly
- [ ] Routing works with different HTTP methods
- [ ] Middleware pipeline executes in correct order
- [ ] Dependency injection works properly
- [ ] Views render with proper data
- [ ] Error handling is implemented
- [ ] Code is well-organized and documented
- [ ] Tests cover core functionality

## Resources
- [Model-View-Controller](https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller)
- [PHP Framework Interop Group](https://www.php-fig.org/)
- [Laravel Documentation](https://laravel.com/docs)
- [Symfony Documentation](https://symfony.com/doc/current/index.html)