# Challenge 12: REST API with JWT Authentication

## Description
This challenge focuses on building a secure REST API with JWT (JSON Web Token) authentication. You'll learn how to implement user registration, login, token refresh, and protected routes while following REST API design principles and security best practices. The challenge covers authentication flows, authorization mechanisms, and creating a robust API foundation.

## Learning Objectives
- Understand JWT authentication flow and implementation
- Implement secure password handling and storage
- Create middleware for protected routes and authorization
- Handle token expiration, refresh, and invalidation
- Apply security best practices for REST APIs
- Design RESTful endpoints following conventions
- Implement proper error handling and validation
- Create comprehensive API documentation

## Requirements
- PHP 8.1+
- Composer
- Node.js 16+
- MySQL database
- Understanding of REST API principles
- Knowledge of JWT and authentication concepts
- Docker (optional, for containerized deployment)

## Features to Implement
1. **Authentication System**
   - User registration with validation
   - Secure login with JWT token generation
   - Password hashing and verification
   - Token refresh mechanism
   - Logout and token invalidation
   
2. **Authorization Framework**
   - Role-based access control (RBAC)
   - Permission-based endpoint protection
   - Middleware for authentication checks
   - CORS configuration and security headers
   
3. **API Design**
   - RESTful endpoint design
   - HTTP status codes and error responses
   - Request validation and sanitization
   - API versioning strategies
   - Rate limiting and abuse prevention
   
4. **Security Measures**
   - Password strength requirements
   - Brute force protection
   - Session management
   - Secure token storage and transmission
   - Input validation and XSS prevention

## Project Structure
```
challenge-12-rest-jwt-api/
├── backend-php/
│   ├── src/
│   │   ├── Auth/
│   │   │   ├── JwtService.php
│   │   │   ├── AuthService.php
│   │   │   └── AuthMiddleware.php
│   │   ├── User/
│   │   │   ├── User.php
│   │   │   ├── UserController.php
│   │   │   ├── UserService.php
│   │   │   └── UserRepository.php
│   │   ├── Http/
│   │   │   ├── Request.php
│   │   │   ├── Response.php
│   │   │   └── Router.php
│   │   └── Exception/
│   │       ├── AuthException.php
│   │       ├── ValidationException.php
│   │       └── UnauthorizedException.php
│   ├── config/
│   │   ├── database.php
│   │   └── jwt.php
│   ├── public/
│   │   └── index.php
│   ├── migrations/
│   ├── tests/
│   ├── composer.json
│   ├── .env.example
│   └── Dockerfile
├── frontend-react/
│   ├── src/
│   │   ├── components/
│   │   │   ├── LoginForm.jsx
│   │   │   ├── RegisterForm.jsx
│   │   │   └── UserDashboard.jsx
│   │   ├── services/
│   │   │   └── apiService.js
│   │   ├── App.jsx
│   │   └── main.jsx
│   ├── package.json
│   ├── vite.config.js
│   └── Dockerfile
├── docker-compose.yml
└── README.md
```

## Setup Instructions

### Prerequisites
- PHP 8.1+
- Composer
- Node.js 16+
- MySQL database
- Docker (optional)

### Backend Setup
1. Navigate to the `backend-php` directory
2. Run `composer install` to install dependencies
3. Copy `.env.example` to `.env` and configure your database settings
4. Run database migrations:
   ```bash
   php migrations/migrate.php
   ```
5. Start the development server:
   ```bash
   php public/index.php
   ```

### Frontend Setup
1. Navigate to the `frontend-react` directory
2. Run `npm install` to install frontend dependencies
3. Run `npm run dev` to start the development server

### Docker Setup
1. From the challenge root directory, run:
   ```bash
   docker-compose up -d
   ```
2. Access the application at `http://localhost:3000`

## API Endpoints

### Authentication
- `POST /api/auth/register` - Register a new user
- `POST /api/auth/login` - Login and receive JWT token
- `POST /api/auth/refresh` - Refresh JWT token
- `POST /api/auth/logout` - Logout and invalidate token

### User Management
- `GET /api/users` - Get all users (protected, admin only)
- `GET /api/users/{id}` - Get user by ID (protected)
- `PUT /api/users/{id}` - Update user (protected, owner or admin)
- `DELETE /api/users/{id}` - Delete user (protected, admin only)
- `GET /api/profile` - Get current user profile (protected)

### Protected Routes
All endpoints requiring authentication must include the Authorization header:
```
Authorization: Bearer <jwt_token>
```

## Evaluation Criteria
- [ ] Secure JWT implementation with proper signing
- [ ] Comprehensive user authentication and authorization
- [ ] Proper error handling and validation
- [ ] RESTful API design following conventions
- [ ] Security best practices implementation
- [ ] Code quality and documentation
- [ ] Test coverage for core functionality

## Resources
- [JWT.io](https://jwt.io/)
- [REST API Design Best Practices](https://restfulapi.net/)
- [OAuth 2.0 and JWT](https://oauth.net/2/jwt/)
- [PHP-JWT Library](https://github.com/firebase/php-jwt)
- [OWASP API Security Top 10](https://owasp.org/www-project-api-security/)