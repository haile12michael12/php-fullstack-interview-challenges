# Challenge 02: Legacy Authentication System

## Description
This challenge focuses on implementing authentication systems compatible with legacy PHP applications. You'll learn how to create secure authentication mechanisms that work with older PHP versions and legacy codebases while maintaining security best practices. The challenge covers session management, password hashing, token-based authentication, and integration with existing systems.

## Learning Objectives
- Understand legacy PHP authentication patterns
- Implement secure session management
- Handle password hashing and verification
- Create token-based authentication systems
- Integrate with existing legacy codebases
- Apply security best practices to older systems
- Migrate legacy authentication to modern standards

## Requirements
- PHP 7.3+ (compatible with legacy systems)
- Composer
- Node.js 16+
- Understanding of HTTP sessions and cookies
- Knowledge of password security principles
- Docker (optional, for containerized deployment)

## Features to Implement
1. **Session-Based Authentication**
   - Secure session management
   - Session timeout and regeneration
   - Cross-site request forgery (CSRF) protection
   - Session storage and persistence
   
2. **Password Security**
   - Password hashing with modern algorithms
   - Password strength validation
   - Account lockout mechanisms
   - Password reset functionality
   
3. **Token Authentication**
   - API token generation and validation
   - Token expiration and refresh
   - Token storage security
   - Multi-factor authentication support
   
4. **Legacy Integration**
   - Compatibility with older PHP versions
   - Integration with existing user databases
   - Migration tools for legacy systems
   - Backward compatibility layers

## Project Structure
```
challenge-02-legacy-auth/
├── backend-php/
│   ├── src/
│   │   ├── Auth/
│   │   │   ├── AuthService.php
│   │   │   ├── SessionManager.php
│   │   │   ├── TokenManager.php
│   │   │   └── PasswordHasher.php
│   │   ├── User/
│   │   │   ├── User.php
│   │   │   ├── UserRepository.php
│   │   │   └── UserService.php
│   │   └── Exception/
│   │       ├── AuthenticationException.php
│   │       ├── AuthorizationException.php
│   │       └── TokenException.php
│   ├── public/
│   │   └── index.php
│   ├── config/
│   ├── tests/
│   ├── composer.json
│   └── Dockerfile
├── frontend-react/
│   ├── src/
│   │   ├── components/
│   │   │   ├── LoginForm.jsx
│   │   │   ├── RegistrationForm.jsx
│   │   │   └── UserProfile.jsx
│   │   ├── services/
│   │   │   └── authService.js
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
- PHP 7.3+
- Composer
- Node.js 16+
- Docker (optional)

### Backend Setup
1. Navigate to the `backend-php` directory
2. Run `composer install` to install dependencies
3. Start the development server with `php public/index.php`

### Frontend Setup
1. Navigate to the `frontend-react` directory
2. Run `npm install` to install dependencies
3. Run `npm run dev` to start the development server

### Docker Setup
1. From the challenge root directory, run `docker-compose up -d`
2. Access the application at `http://localhost:3000`

## API Endpoints
- `POST /api/auth/login` - User login with credentials
- `POST /api/auth/logout` - User logout
- `POST /api/auth/register` - User registration
- `POST /api/auth/password/reset` - Password reset request
- `POST /api/auth/password/change` - Change password
- `GET /api/user/profile` - Get user profile (authenticated)
- `POST /api/auth/token` - Generate API token

## Evaluation Criteria
- [ ] Secure authentication implementation
- [ ] Proper session and token management
- [ ] Password security best practices
- [ ] Legacy system compatibility
- [ ] CSRF protection implementation
- [ ] Code organization and documentation
- [ ] Comprehensive test coverage

## Resources
- [PHP Session Security](https://www.php.net/manual/en/session.security.php)
- [Password Hashing in PHP](https://www.php.net/manual/en/function.password-hash.php)
- [OWASP Authentication Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/Authentication_Cheat_Sheet.html)
- [JSON Web Tokens](https://jwt.io/)