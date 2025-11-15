# Challenge 24: Abstract Factory Pattern

This challenge demonstrates the Abstract Factory design pattern implementation in a full-stack PHP and React application.

## Project Structure

```
challenge-24-abstract-factory/
├── backend-php/
│   ├── src/
│   │   ├── Factory/              # Abstract factory implementations
│   │   ├── Connection/           # Database connection implementations
│   │   ├── Pool/                 # Connection pooling implementation
│   │   ├── NoSQL/                # MongoDB/Redis adapters
│   │   ├── Http/
│   │   │   ├── Controller/       # API controllers
│   │   │   └── Middleware/       # Request middleware
│   │   ├── Security/             # Authentication and security
│   │   ├── Schema/               # Schema management
│   │   ├── Queue/                # Background job processing
│   │   └── Application/          # Core application components
│   ├── public/                   # Publicly accessible files
│   ├── tests/                    # Unit and integration tests
│   ├── composer.json             # PHP dependencies
│   ├── Dockerfile                # Backend Docker configuration
│   └── README.md                 # Backend documentation
│
├── frontend-react/
│   ├── src/
│   │   ├── components/           # React components
│   │   ├── services/             # API service layer
│   │   └── App.jsx               # Main application component
│   ├── public/                   # Static assets
│   ├── package.json              # Frontend dependencies
│   ├── Dockerfile                # Frontend Docker configuration
│   └── README.md                 # Frontend documentation
│
├── infra/
│   ├── docker-compose.yml        # Docker orchestration
│   ├── k8s/                      # Kubernetes deployment files
│   └── traefik/                  # Reverse proxy configuration
│
├── .github/
│   └── workflows/                # GitHub Actions CI/CD
│
├── Makefile                      # Development commands
└── README.md                     # Project documentation
```

## Features

### Abstract Factory Pattern
- Database factory interface and implementations (MySQL, PostgreSQL, SQLite)
- Connection pooling implementation
- NoSQL adapters (MongoDB, Redis)
- Query builder and schema manager creation

### Backend Components
- RESTful API controllers
- Authentication middleware
- Rate limiting middleware
- JWT-based authentication
- Background job dispatcher
- Configuration management

### Frontend Components
- Abstract factory demo interface
- Database selector component
- Query executor component
- Flowchart visualizer
- Authentication widget

## Getting Started

### Prerequisites
- Docker and Docker Compose
- Node.js (for local development)
- PHP 8.1+ (for local development)

### Running with Docker

```bash
# Clone the repository
git clone <repository-url>
cd challenge-24-abstract-factory

# Start all services
make start

# Or using docker-compose directly
docker-compose up -d
```

### Using Makefile Commands

```bash
# Setup project dependencies
make setup

# Start all services
make start

# Stop all services
make stop

# Run all tests
make test

# Open shell in PHP container
make shell-php

# View service logs
make logs
```

## API Endpoints

### Factory Endpoints
- `GET /api/factory` - Factory overview
- `GET /api/factory/mysql` - Create MySQL connection
- `GET /api/factory/postgresql` - Create PostgreSQL connection
- `GET /api/factory/sqlite` - Create SQLite connection
- `GET /api/factory/pool` - Connection pooling demo

### Authentication Endpoints
- `POST /api/auth/login` - User login
- `POST /api/auth/logout` - User logout
- `POST /api/auth/register` - User registration

## Testing

### PHP Tests
```bash
cd backend-php
composer test
```

### JavaScript Tests
```bash
cd frontend-react
npm test
```

## Abstract Factory Pattern Implementation

The Abstract Factory pattern provides an interface for creating families of related or dependent objects without specifying their concrete classes.

### Key Components

1. **Factory Interface** - Defines methods for creating products
2. **Concrete Factories** - Implement the factory interface for specific product families
3. **Product Interfaces** - Define the interface for products
4. **Concrete Products** - Implement the product interfaces

### Benefits
- Promotes consistency among products
- Makes it easy to introduce new variants
- Avoids tight coupling between products
- Follows the Single Responsibility Principle
- Follows the Open/Closed Principle

## License

This project is licensed under the MIT License.
