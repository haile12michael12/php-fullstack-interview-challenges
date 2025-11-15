# Abstract Factory Database System

A full-stack PHP/React application demonstrating the Abstract Factory design pattern for database connections.

## Project Structure

```
abstract-factory-db-system/
│
├── backend/                             # PHP backend (API server)
│   ├── public/
│   │   ├── index.php                    # Front controller
│   │   └── .htaccess                    # Apache routing
│   │
│   ├── src/
│   │   ├── Core/
│   │   │   ├── Autoloader.php
│   │   │   ├── Config.php
│   │   │   ├── Logger.php
│   │   │   └── Env.php
│   │   │
│   │   ├── Interfaces/
│   │   │   ├── DBConnectionInterface.php
│   │   │   └── DBFactoryInterface.php
│   │   │
│   │   ├── Factories/
│   │   │   ├── MySQLFactory.php
│   │   │   ├── PostgresFactory.php
│   │   │   ├── SQLiteFactory.php
│   │   │   └── DBFactoryProvider.php
│   │   │
│   │   ├── Drivers/
│   │   │   ├── MySQLConnection.php
│   │   │   ├── PostgresConnection.php
│   │   │   └── SQLiteConnection.php
│   │   │
│   │   ├── Services/
│   │   │   ├── ConnectionManager.php
│   │   │   ├── AuthService.php
│   │   │   └── QueryService.php
│   │   │
│   │   ├── Controllers/
│   │   │   ├── ConnectionController.php
│   │   │   ├── QueryController.php
│   │   │   └── AuthController.php
│   │   │
│   │   └── Routes/
│   │       └── api.php
│   │
│   ├── tests/
│   │   ├── FactoryTest.php
│   │   ├── ConnectionTest.php
│   │   └── QueryServiceTest.php
│   │
│   ├── vendor/
│   ├── .env
│   ├── composer.json
│   └── bootstrap.php
│
├── frontend/                            # React dashboard
│   ├── src/
│   │   ├── api/
│   │   │   ├── axiosClient.js
│   │   │   └── dbApi.js
│   │   │
│   │   ├── store/
│   │   │   └── connectionStore.js       # Zustand for global state
│   │   │
│   │   ├── components/
│   │   │   ├── DatabaseSelector.jsx
│   │   │   ├── QueryExecutor.jsx
│   │   │   ├── ConnectionStatusCard.jsx
│   │   │   └── LoginForm.jsx
│   │   │
│   │   ├── pages/
│   │   │   ├── Dashboard.jsx
│   │   │   ├── Login.jsx
│   │   │   └── Settings.jsx
│   │   │
│   │   ├── App.jsx
│   │   └── main.jsx
│   │
│   ├── public/
│   │   └── index.html
│   │
│   ├── package.json
│   ├── tailwind.config.js
│   ├── vite.config.js
│   └── .env
│
├── docker-compose.yml                   # MySQL, Postgres, and PHP containers
├── README.md
└── .gitignore
```

## Features

- **Abstract Factory Pattern**: Implementation of the Abstract Factory design pattern for creating database connections
- **Multiple Database Support**: MySQL, PostgreSQL, and SQLite drivers
- **RESTful API**: PHP backend with clean API endpoints
- **Modern Frontend**: React dashboard with Tailwind CSS
- **State Management**: Global state management with Zustand
- **Docker Integration**: Containerized development environment
- **Testing**: Unit tests for factory and connection classes

## Design Pattern Implementation

The Abstract Factory pattern is implemented through:

1. **Interfaces**:
   - `DBFactoryInterface`: Defines the contract for creating database connections
   - `DBConnectionInterface`: Defines the contract for database operations

2. **Factories**:
   - `MySQLFactory`: Creates MySQL connections
   - `PostgresFactory`: Creates PostgreSQL connections
   - `SQLiteFactory`: Creates SQLite connections
   - `DBFactoryProvider`: Factory provider for getting the appropriate factory

3. **Products**:
   - `MySQLConnection`: MySQL connection implementation
   - `PostgresConnection`: PostgreSQL connection implementation
   - `SQLiteConnection`: SQLite connection implementation

## Getting Started

### Prerequisites

- Docker and Docker Compose
- Node.js (for local development)
- PHP 7.4 or higher (for local development)

### Installation with Docker

1. Clone the repository:
   ```bash
   git clone <repository-url>
   cd abstract-factory-db-system
   ```

2. Start the services:
   ```bash
   docker-compose up -d
   ```

3. Install PHP dependencies:
   ```bash
   docker-compose exec php composer install
   ```

4. Install frontend dependencies:
   ```bash
   docker-compose exec frontend npm install
   ```

5. Access the application:
   - Frontend: http://localhost:3000
   - Backend API: http://localhost:8000

### Local Development

#### Backend Setup

1. Navigate to the backend directory:
   ```bash
   cd backend
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Start the PHP development server:
   ```bash
   php -S localhost:8000 -t public
   ```

#### Frontend Setup

1. Navigate to the frontend directory:
   ```bash
   cd frontend
   ```

2. Install dependencies:
   ```bash
   npm install
   ```

3. Start the development server:
   ```bash
   npm run dev
   ```

## API Endpoints

- `POST /api/auth/login` - User authentication
- `POST /api/connection/test` - Test database connection
- `POST /api/query/execute` - Execute SQL queries

## Testing

### Backend Tests

Run PHP unit tests:
```bash
cd backend
./vendor/bin/phpunit
```

### Frontend Tests

Run React tests:
```bash
cd frontend
npm test
```

## License

This project is licensed under the MIT License.