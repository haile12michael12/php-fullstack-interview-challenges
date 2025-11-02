# Challenge 01: PHP gRPC Echo with React

## Description
This challenge focuses on implementing a gRPC echo service using PHP and a React frontend. You'll learn how to set up gRPC communication between a PHP backend and a JavaScript frontend using Envoy as a gRPC-web proxy. The challenge includes both a REST echo endpoint for quick development and a full gRPC implementation.

## Learning Objectives
- Understand gRPC communication patterns
- Implement gRPC services in PHP
- Set up gRPC-web proxy with Envoy
- Create React frontend that communicates with gRPC backend
- Handle protocol buffer serialization
- Configure Docker for multi-service applications

## Requirements
- PHP 7.3+
- Composer
- Node.js 16+
- Docker (for full gRPC implementation)
- Protocol Buffer Compiler (protoc)
- gRPC PHP extension
- gRPC PHP plugin

## Features Implemented
1. **gRPC Echo Service**
   - PHP gRPC server implementation
   - Protocol buffer definition
   - Echo service with request/response
   
2. **React Frontend**
   - gRPC-web client implementation
   - User interface for testing echo functionality
   - Real-time communication
   
3. **Envoy Proxy**
   - gRPC-web to gRPC translation
   - HTTP/2 support
   - Load balancing capabilities
   
4. **Docker Configuration**
   - Multi-container setup
   - Service networking
   - Development and production configurations

## Project Structure
```
challenge-01-grpc-echo/
├── backend/
│   ├── app/
│   │   └── EchoService.php
│   ├── proto/
│   │   └── echo.proto
│   ├── docker/
│   │   ├── php/Dockerfile
│   │   └── roadrunner/Dockerfile
│   ├── composer.json
│   ├── server.php
│   ├── roadrunner.yaml
│   └── GENERATE.md
├── frontend/
│   ├── src/
│   │   ├── components/
│   │   ├── App.jsx
│   │   └── main.jsx
│   ├── package.json
│   ├── vite.config.js
│   └── index.html
├── docker-compose.yml
├── envoy.yaml
└── README.md
```

## Setup Instructions

### Quick Start (REST Mode)
For rapid development and testing, you can use the REST echo endpoint:

1. Start the PHP REST dev server:
   ```bash
   cd backend
   php -S 0.0.0.0:8080 server.php
   ```
2. Start the frontend:
   ```bash
   cd frontend
   npm install
   npm run dev
   ```
3. Open your browser to: http://localhost:3000

### Full gRPC Implementation
To run the complete gRPC stack with Docker:

1. Generate PHP and JavaScript bindings:
   ```bash
   cd backend
   # Follow instructions in GENERATE.md
   ```
2. Build and run with Docker:
   ```bash
   docker-compose up --build
   ```
3. Access the application at: http://localhost:3000

## API Endpoints
- `POST /echo` (REST) - Echo the provided message
- `EchoService.Echo` (gRPC) - gRPC echo method

## Evaluation Criteria
- Successful gRPC communication between services
- Proper protocol buffer implementation
- Correct Envoy proxy configuration
- Functional React frontend
- Docker configuration working correctly
- Code organization and documentation

## Resources
- [gRPC Documentation](https://grpc.io/docs/)
- [gRPC PHP Quick Start](https://grpc.io/docs/languages/php/quickstart/)
- [Envoy Proxy Documentation](https://www.envoyproxy.io/docs/envoy/latest/)
- [Protocol Buffers](https://developers.google.com/protocol-buffers)
- [gRPC-web](https://github.com/grpc/grpc-web)