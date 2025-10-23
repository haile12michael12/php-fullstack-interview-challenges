# Challenge 39: CSRF Protection

## Description
This challenge focuses on implementing comprehensive CSRF protection with tokens. You'll learn to protect web applications from Cross-Site Request Forgery attacks.

## Learning Objectives
- Implement CSRF protection mechanisms
- Generate and validate CSRF tokens
- Protect forms and AJAX requests
- Understand CSRF attack vectors
- Implement token storage and rotation
- Handle token validation failures

## Requirements
- PHP 8.1+
- Composer
- Understanding of web security concepts
- Knowledge of HTTP protocols

## Features to Implement
1. CSRF Token Manager
   - Token generation
   - Token validation
   - Token storage
   - Token rotation

2. Middleware Integration
   - Request validation
   - Token injection
   - Error handling
   - Session management

3. Frontend Integration
   - Token injection in forms
   - AJAX request protection
   - Token refresh mechanisms
   - User experience considerations

4. Advanced Features
   - Double submit cookie pattern
   - Same-site cookie attributes
   - Token timeout management
   - Logging and monitoring

## Project Structure
```
challenge-39-csrf-protection/
├── backend-php/
│   ├── config/
│   ├── public/
│   ├── src/
│   │   ├── Csrf/
│   │   │   ├── CsrfTokenManager.php
│   │   │   ├── CsrfMiddleware.php
│   │   │   ├── TokenStorage.php
│   │   │   └── TokenValidator.php
│   │   ├── Protection/
│   │   │   ├── FormProtection.php
│   │   │   ├── AjaxProtection.php
│   │   │   └── SessionManager.php
│   │   └── Service/
│   │       └── SecurityService.php
│   ├── tests/
│   ├── composer.json
│   └── server.php
├── frontend-react/
│   ├── src/
│   │   ├── components/
│   │   │   ├── SecureForm.jsx
│   │   │   └── CsrfProvider.jsx
│   │   └── services/
│   │       └── csrfService.js
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
- `GET /api/csrf/token` - Generate new CSRF token
- `POST /api/csrf/validate` - Validate CSRF token
- `POST /api/forms/secure` - Submit protected form
- `POST /api/ajax/secure` - Make protected AJAX request
- `GET /api/csrf/status` - Get CSRF protection status

## Evaluation Criteria
- Proper implementation of CSRF protection mechanisms
- Effective token generation and validation
- Robust middleware integration
- Clean frontend integration
- Comprehensive error handling
- Comprehensive test coverage

## Resources
- [Cross-Site Request Forgery](https://en.wikipedia.org/wiki/Cross-site_request_forgery)
- [OWASP CSRF Prevention](https://cheatsheetseries.owasp.org/cheatsheets/Cross-Site_Request_Forgery_Prevention_Cheat_Sheet.html)
- [CSRF Tokens](https://www.owasp.org/index.php/Cross-Site_Request_Forgery_(CSRF)_Prevention_Cheat_Sheet)