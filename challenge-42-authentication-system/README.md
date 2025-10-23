# Challenge 42: Authentication System

## Description
This challenge focuses on implementing a complete authentication system with sessions. You'll learn to build secure user authentication with proper session management.

## Learning Objectives
- Implement a complete authentication system
- Manage user sessions securely
- Handle password hashing and verification
- Implement login and logout functionality
- Understand authentication best practices
- Handle authentication errors gracefully

## Requirements
- PHP 8.1+
- Composer
- Understanding of web authentication
- Knowledge of session management

## Features to Implement
1. Authentication Manager
   - User login and logout
   - Password hashing
   - Session management
   - Authentication validation

2. User Management
   - User registration
   - Password reset
   - Account verification
   - User profile management

3. Security Features
   - Password strength validation
   - Brute force protection
   - Session fixation prevention
   - Remember me functionality

4. Advanced Features
   - Multi-factor authentication
   - OAuth integration
   - Single sign-on (SSO)
   - Activity logging

## Project Structure
```
challenge-42-authentication-system/
├── backend-php/
│   ├── config/
│   ├── public/
│   ├── src/
│   │   ├── Auth/
│   │   │   ├── AuthManager.php
│   │   │   ├── SessionManager.php
│   │   │   ├── PasswordHasher.php
│   │   │   └── UserProvider.php
│   │   ├── User/
│   │   │   ├── UserService.php
│   │   │   ├── UserRepository.php
│   │   │   └── User.php
│   │   └── Service/
│   │       └── AuthenticationService.php
│   ├── tests/
│   ├── composer.json
│   └── server.php
├── frontend-react/
│   ├── src/
│   │   ├── components/
│   │   │   ├── LoginForm.jsx
│   │   │   ├── RegistrationForm.jsx
│   │   │   └── UserProfile.jsx
│   │   └── services/
│   │       └── authService.js
│   ├── package.json
│   └── vite.config.js
└── README.md
```

## Setup Instructions
1. Navigate to the `backend-php` directory
2. Run `composer install` to install dependencies
3. Copy `.env.example` to `.env` and configure your settings
4. Start the development server with `php server.php`
5. Navigate to the `frontend-react` directory
6. Run `npm install` to install frontend dependencies
7. Run `npm run dev` to start the frontend development server

## API Endpoints
- `POST /api/auth/login` - User login
- `POST /api/auth/logout` - User logout
- `POST /api/auth/register` - User registration
- `POST /api/auth/reset-password` - Password reset
- `GET /api/auth/user` - Get current user
- `POST /api/auth/refresh` - Refresh authentication

## Evaluation Criteria
- Proper implementation of authentication mechanisms
- Secure password handling
- Robust session management
- Clean user interface for authentication
- Comprehensive error handling
- Comprehensive test coverage

## Resources
- [Authentication](https://en.wikipedia.org/wiki/Authentication)
- [OWASP Authentication Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/Authentication_Cheat_Sheet.html)
- [Session Management](https://cheatsheetseries.owasp.org/cheatsheets/Session_Management_Cheat_Sheet.html)