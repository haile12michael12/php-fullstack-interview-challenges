# Challenge 30: Command Pattern

## Description
This challenge focuses on implementing the Command design pattern to build a queue worker with command objects and undo functionality. You'll learn to encapsulate requests as objects and support undoable operations.

## Learning Objectives
- Implement the Command design pattern
- Encapsulate requests as objects
- Support undoable operations
- Create command queues and workers
- Implement command history
- Understand command-receiver relationships

## Requirements
- PHP 8.1+
- Composer
- Understanding of design patterns
- Knowledge of queue processing concepts

## Features to Implement
1. Command Interface
   - Execute method
   - Undo method
   - Command metadata

2. Concrete Commands
   - File operations (create, delete, modify)
   - Database operations (insert, update, delete)
   - Email sending command
   - Image processing command

3. Invoker Class
   - Command execution
   - Command queuing
   - Undo/redo functionality

4. Receiver Classes
   - File system manager
   - Database manager
   - Email service
   - Image processor

## Project Structure
```
challenge-30-command-pattern/
├── backend-php/
│   ├── config/
│   ├── public/
│   ├── src/
│   │   ├── Command/
│   │   │   ├── CommandInterface.php
│   │   │   ├── CreateFileCommand.php
│   │   │   ├── DeleteFileCommand.php
│   │   │   ├── SendEmailCommand.php
│   │   │   ├── ProcessImageCommand.php
│   │   │   └── DatabaseCommand.php
│   │   ├── Invoker/
│   │   │   ├── CommandQueue.php
│   │   │   ├── CommandHistory.php
│   │   │   └── Worker.php
│   │   ├── Receiver/
│   │   │   ├── FileSystemManager.php
│   │   │   ├── DatabaseManager.php
│   │   │   ├── EmailService.php
│   │   │   └── ImageProcessor.php
│   │   └── Service/
│   │       └── CommandService.php
│   ├── tests/
│   ├── composer.json
│   └── server.php
├── frontend-react/
│   ├── src/
│   │   ├── components/
│   │   │   ├── CommandQueue.jsx
│   │   │   └── HistoryViewer.jsx
│   │   └── services/
│   │       └── commandService.js
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
- `POST /api/commands/queue` - Queue a new command
- `GET /api/commands/history` - Get command history
- `POST /api/commands/undo` - Undo last command
- `POST /api/commands/redo` - Redo last undone command
- `GET /api/queue/status` - Get queue status
- `DELETE /api/commands/clear` - Clear command history

## Evaluation Criteria
- Proper implementation of Command pattern interfaces
- Effective command queuing and execution
- Robust undo/redo functionality
- Clean separation of command and receiver logic
- Comprehensive error handling
- Comprehensive test coverage

## Resources
- [Command Pattern](https://en.wikipedia.org/wiki/Command_pattern)
- [PHP Design Patterns](https://designpatternsphp.readthedocs.io/en/latest/Behavioral/Command/README.html)
- [Queue Processing Patterns](https://www.enterpriseintegrationpatterns.com/patterns/messaging/MessageQueue.html)