# Challenge 35: Migration System

## Description
This challenge focuses on creating a database migration system similar to Laravel. You'll learn to manage database schema changes programmatically and ensure consistent database states across environments.

## Learning Objectives
- Implement a database migration system
- Manage database schema changes programmatically
- Ensure consistent database states across environments
- Create reversible migrations
- Implement migration versioning
- Understand database evolution patterns

## Requirements
- PHP 8.1+
- Composer
- Understanding of database schema concepts
- Knowledge of version control systems

## Features to Implement
1. Migration Interface
   - Up and down methods
   - Migration metadata
   - Execution tracking
   - Dependency management

2. Migration Runner
   - Migration discovery
   - Execution ordering
   - Status tracking
   - Rollback functionality

3. Migration Generator
   - Migration file creation
   - Template generation
   - Naming conventions
   - Timestamp management

4. Advanced Features
   - Batch operations
   - Migration groups
   - Environment-specific migrations
   - Migration validation

## Project Structure
```
challenge-35-migration-system/
├── backend-php/
│   ├── config/
│   ├── public/
│   ├── src/
│   │   ├── Migration/
│   │   │   ├── MigrationInterface.php
│   │   │   ├── MigrationRunner.php
│   │   │   ├── MigrationGenerator.php
│   │   │   └── MigrationRepository.php
│   │   ├── Schema/
│   │   │   ├── Blueprint.php
│   │   │   ├── TableBuilder.php
│   │   │   ├── ColumnBuilder.php
│   │   │   └── IndexBuilder.php
│   │   └── Service/
│   │       └── MigrationService.php
│   ├── tests/
│   ├── composer.json
│   └── server.php
├── frontend-react/
│   ├── src/
│   │   ├── components/
│   │   │   ├── MigrationManager.jsx
│   │   │   └── MigrationStatus.jsx
│   │   └── services/
│   │       └── migrationService.js
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
- `GET /api/migrations` - List available migrations
- `POST /api/migrations/run` - Run pending migrations
- `POST /api/migrations/rollback` - Rollback last migration
- `POST /api/migrations/reset` - Reset all migrations
- `POST /api/migrations/generate` - Generate new migration
- `GET /api/migrations/status` - Get migration status

## Evaluation Criteria
- Proper implementation of migration interface
- Effective migration execution and tracking
- Robust rollback functionality
- Clean migration file generation
- Comprehensive error handling
- Comprehensive test coverage

## Resources
- [Database Migration](https://en.wikipedia.org/wiki/Schema_migration)
- [Laravel Migrations](https://laravel.com/docs/migrations)
- [Database Versioning](https://martinfowler.com/articles/evodb.html)