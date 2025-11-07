# User Service

## Description
The User Service manages user accounts, authentication, and profiles for the Docker Microservices Challenge.

## Features
- User registration and management
- Authentication and authorization
- Profile management

## API Endpoints
- `GET /users/{id}` - Get user by ID
- `POST /users` - Create a new user

## Technologies
- PHP 8.1
- MySQL
- Docker

## Setup
1. Build the service: `docker-compose build user-service`
2. Start the service: `docker-compose up user-service`
3. Access the service at `http://localhost:8081`