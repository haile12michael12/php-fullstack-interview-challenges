# Challenge 22: Magic Methods

This challenge demonstrates the power of PHP magic methods in building a full-stack application with an ORM, dynamic proxies, and fluent interfaces.

## Project Structure

```
challenge-22-magic-methods/
├── backend-php/
│   ├── src/
│   │   ├── ORM/              # Object-Relational Mapping classes
│   │   ├── Magic/            # Magic method implementations
│   │   ├── Database/         # Database connection and schema management
│   │   ├── Controller/       # API controllers
│   │   ├── Model/            # Data models
│   │   └── Helpers/          # Utility classes
│   ├── public/               # Publicly accessible files
│   ├── config/               # Configuration files
│   ├── tests/                # Unit tests
│   ├── composer.json         # PHP dependencies
│   ├── Dockerfile            # Backend Docker configuration
│   ├── phpunit.xml           # PHPUnit configuration
│   └── README.md             # Backend documentation
│
├── frontend-react/
│   ├── src/
│   │   ├── components/       # React components
│   │   ├── pages/            # Page components
│   │   ├── services/         # API service layer
│   │   └── App.jsx           # Main application component
│   ├── public/               # Static assets
│   ├── package.json          # Frontend dependencies
│   ├── vite.config.js        # Vite configuration
│   ├── Dockerfile            # Frontend Docker configuration
│   └── README.md             # Frontend documentation
│
├── docker-compose.yml        # Docker orchestration
└── README.md                 # Project documentation
```

## Key Features

### PHP Magic Methods Demonstrated

1. **Construction and Destruction**
   - `__construct()` - Object initialization
   - `__destruct()` - Object cleanup

2. **Property Access**
   - `__get()` - Reading inaccessible properties
   - `__set()` - Writing to inaccessible properties
   - `__isset()` - Checking if properties are set
   - `__unset()` - Unsetting properties

3. **Method Calls**
   - `__call()` - Calling inaccessible methods
   - `__callStatic()` - Calling inaccessible static methods

4. **Serialization**
   - `__sleep()` - Preparing object for serialization
   - `__wakeup()` - Restoring object from serialization

5. **Object Representation**
   - `__toString()` - Converting object to string
   - `__invoke()` - Making object callable
   - `__debugInfo()` - Custom debug information

6. **Cloning**
   - `__clone()` - Object cloning behavior

### Components

1. **ORM (Object-Relational Mapping)**
   - Entity base class with magic property access
   - Model class with dynamic query building
   - QueryBuilder with fluent interface
   - Relation handling

2. **Magic Classes**
   - FluentInterface for method chaining
   - DynamicProxy for intercepting method calls
   - MethodInterceptor for aspect-oriented programming

3. **Database**
   - Connection management
   - Schema builder
   - Migration system

4. **Frontend**
   - React components for each feature
   - Interactive demos
   - Real-time data visualization

## Getting Started

### Prerequisites

- Docker and Docker Compose
- Node.js (for local development)
- PHP 8.1+ (for local development)

### Running with Docker

```bash
# Clone the repository
git clone <repository-url>
cd challenge-22-magic-methods

# Start the services
docker-compose up -d

# Access the applications
# Frontend: http://localhost:3000
# Backend API: http://localhost:8000
```

### Local Development

#### Backend (PHP)

```bash
cd backend-php

# Install PHP dependencies
composer install

# Run tests
composer test

# Start development server
php -S localhost:8000 -t public
```

#### Frontend (React)

```bash
cd frontend-react

# Install dependencies
npm install

# Start development server
npm run dev
```

## API Endpoints

### Magic Methods
- `GET /api/magic` - Magic methods overview
- `GET /api/magic/fluent` - Fluent interface demo
- `GET /api/magic/proxy` - Dynamic proxy demo
- `GET /api/magic/interceptor` - Method interceptor demo

### Entities
- `GET /api/entities` - Entities overview
- `GET /api/entities/users` - List all users
- `GET /api/entities/users/{id}` - Get user by ID
- `POST /api/entities/users` - Create new user
- `PUT /api/entities/users/{id}` - Update user
- `DELETE /api/entities/users/{id}` - Delete user

### Query Builder
- `GET /api/query` - Query builder overview
- `GET /api/query/users` - Query users with filters
- `GET /api/query/posts` - Query posts with filters
- `POST /api/query/custom` - Execute custom query

## Testing

### Backend Tests

```bash
cd backend-php
composer test
```

### Frontend Tests

```bash
cd frontend-react
npm run test
```

## License

This project is licensed under the MIT License.