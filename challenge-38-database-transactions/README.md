# Challenge 38: Database Transactions

## Description
This challenge focuses on implementing nested transaction support with savepoints. You'll learn to manage complex database operations that must succeed or fail together.

## Learning Objectives
- Implement database transaction management
- Support nested transactions with savepoints
- Ensure data consistency and integrity
- Handle transaction rollback and commit
- Understand ACID properties
- Manage transaction isolation levels

## Requirements
- PHP 8.1+
- Composer
- Understanding of database transactions
- Knowledge of ACID properties

## Features to Implement
1. Transaction Manager
   - Transaction lifecycle management
   - Nested transaction support
   - Savepoint handling
   - Isolation level control

2. Transaction Operations
   - Begin, commit, and rollback
   - Savepoint creation and release
   - Transaction state tracking
   - Error handling and recovery

3. Advanced Features
   - Transaction timeout management
   - Deadlock detection and resolution
   - Transaction logging
   - Performance monitoring

4. Integration Points
   - ORM integration
   - Query builder support
   - Connection pooling compatibility
   - Event system

## Project Structure
```
challenge-38-database-transactions/
├── backend-php/
│   ├── config/
│   ├── public/
│   ├── src/
│   │   ├── Transaction/
│   │   │   ├── TransactionManager.php
│   │   │   ├── TransactionInterface.php
│   │   │   ├── Savepoint.php
│   │   │   └── TransactionState.php
│   │   ├── Manager/
│   │   │   ├── ConnectionManager.php
│   │   │   └── IsolationManager.php
│   │   └── Service/
│   │       └── TransactionService.php
│   ├── tests/
│   ├── composer.json
│   └── server.php
├── frontend-react/
│   ├── src/
│   │   ├── components/
│   │   │   ├── TransactionMonitor.jsx
│   │   │   └── TransactionLog.jsx
│   │   └── services/
│   │       └── transactionService.js
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
- `POST /api/transactions/begin` - Begin new transaction
- `POST /api/transactions/commit` - Commit current transaction
- `POST /api/transactions/rollback` - Rollback current transaction
- `POST /api/transactions/savepoint` - Create savepoint
- `POST /api/transactions/release` - Release savepoint
- `GET /api/transactions/status` - Get transaction status

## Evaluation Criteria
- Proper implementation of transaction management
- Effective nested transaction support with savepoints
- Robust error handling and recovery
- Clean transaction state management
- Comprehensive logging and monitoring
- Comprehensive test coverage

## Resources
- [Database Transaction](https://en.wikipedia.org/wiki/Database_transaction)
- [ACID Properties](https://en.wikipedia.org/wiki/ACID)
- [Savepoints](https://en.wikipedia.org/wiki/Savepoint)