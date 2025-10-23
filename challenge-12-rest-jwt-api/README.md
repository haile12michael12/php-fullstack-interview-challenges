# Challenge 12: REST API with JWT Authentication

## Overview
This challenge focuses on building a secure REST API with JWT (JSON Web Token) authentication. You'll learn how to implement user registration, login, token refresh, and protected routes.

## Features
- User registration and authentication
- JWT token generation and validation
- Protected API endpoints
- Password hashing and security best practices
- Refresh token implementation

## Technologies Used
- PHP 8.1+
- JWT Authentication
- MySQL database
- React frontend with Axios for API calls
- Vite for frontend build tooling

## Project Structure
```
challenge-12-rest-jwt-api/
├── backend-php/
│   ├── config/
│   ├── public/
│   ├── src/
│   ├── composer.json
│   └── .env
└── frontend-react/
    ├── src/
    ├── package.json
    └── vite.config.js
```

## Setup Instructions
1. Navigate to the `backend-php` directory
2. Run `composer install` to install dependencies
3. Copy `.env.example` to `.env` and configure your database settings
4. Run database migrations
5. Navigate to the `frontend-react` directory
6. Run `npm install` to install frontend dependencies
7. Run `npm run dev` to start the development server

## API Endpoints
- `POST /api/auth/register` - Register a new user
- `POST /api/auth/login` - Login and receive JWT token
- `POST /api/auth/refresh` - Refresh JWT token
- `GET /api/users` - Get all users (protected)
- `GET /api/users/{id}` - Get user by ID (protected)
- `PUT /api/users/{id}` - Update user (protected)
- `DELETE /api/users/{id}` - Delete user (protected)

## Learning Objectives
- Understand JWT authentication flow
- Implement secure password handling
- Create middleware for protected routes
- Handle token expiration and refresh
- Apply security best practices for REST APIs