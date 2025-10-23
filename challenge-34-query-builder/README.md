# Challenge 34: Query Builder

## Description
This challenge focuses on implementing a secure query builder with prepared statements. You'll learn to create a fluent interface for building database queries while ensuring security through parameter binding.

## Learning Objectives
- Implement a query builder with fluent interface
- Use prepared statements for security
- Create database-agnostic query construction
- Implement parameter binding and escaping
- Build complex query operations
- Understand SQL injection prevention

## Requirements
- PHP 8.1+
- Composer
- Understanding of SQL and database concepts
- Knowledge of prepared statements

## Features to Implement
1. Query Builder Core
   - SELECT, INSERT, UPDATE, DELETE operations
   - WHERE clause building
   - JOIN operations
   - ORDER BY and LIMIT clauses
   - GROUP BY and HAVING clauses

2. Security Features
   - Parameter binding
   - SQL injection prevention
   - Input validation
   - Query sanitization
   - Escaping mechanisms

3. Advanced Operations
   - Subqueries support
   - UNION operations
   - Aggregation functions
   - Conditional expressions
   - Raw SQL integration

4. Database Integration
   - Multiple database support
   - Query compilation
   - Result set handling
   - Performance optimization
   - Debugging tools

## Project Structure
```
challenge-34-query-builder/
├── backend-php/
│   ├── config/
│   ├── public/
│   ├── src/
│   │   ├── Query/
│   │   │   ├── QueryBuilder.php
│   │   │   ├── SelectQuery.php
│   │   │   ├── InsertQuery.php
│   │   │   ├── UpdateQuery.php
│   │   │   └── DeleteQuery.php
│   │   ├── Grammar/
│   │   │   ├── GrammarInterface.php
│   │   │   ├── MySQLGrammar.php
│   │   │   ├── PostgreSQLGrammar.php
│   │   │   └── SQLiteGrammar.php
│   │   └── Service/
│   │       └── QueryService.php
│   ├── tests/
│   ├── composer.json
│   └── server.php
├── frontend-react/
│   ├── src/
│   │   ├── components/
│   │   │   ├── QueryBuilder.jsx
│   │   │   └── QueryResults.jsx
│   │   └── services/
│   │       └── queryService.js
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
- `POST /api/query/build` - Build and execute query
- `POST /api/query/select` - Build SELECT query
- `POST /api/query/insert` - Build INSERT query
- `POST /api/query/update` - Build UPDATE query
- `POST /api/query/delete` - Build DELETE query
- `GET /api/query/history` - Get query history

## Evaluation Criteria
- Proper implementation of fluent query builder interface
- Effective use of prepared statements for security
- Robust parameter binding and escaping
- Support for complex query operations
- Clean and maintainable code structure
- Comprehensive test coverage

## Resources
- [Query Builder Pattern](https://en.wikipedia.org/wiki/Query_builder_pattern)
- [Prepared Statements](https://www.php.net/manual/en/pdo.prepared-statements.php)
- [SQL Injection Prevention](https://owasp.org/www-community/attacks/SQL_Injection)