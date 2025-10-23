# Challenge 27: Observer Pattern

## Description
This challenge focuses on implementing the Observer design pattern to build an event system with observers and subjects. You'll learn to create loosely coupled components that react to state changes in other objects.

## Learning Objectives
- Implement the Observer design pattern
- Create event-driven architectures
- Build loosely coupled systems
- Understand push vs pull notification models
- Implement subject-observer relationships
- Handle observer lifecycle management

## Requirements
- PHP 8.1+
- Composer
- Understanding of design patterns
- Knowledge of event-driven programming

## Features to Implement
1. Observer Interface
   - Update method definition
   - Notification handling
   - State synchronization

2. Subject Interface
   - Observer registration
   - Observer removal
   - Notification dispatching

3. Concrete Implementations
   - News agency (subject)
   - News channels (observers)
   - User notification system
   - Event logging system

4. Advanced Features
   - Priority-based observer execution
   - Conditional notifications
   - Observer filtering
   - Asynchronous notifications

## Project Structure
```
challenge-27-observer-pattern/
├── backend-php/
│   ├── config/
│   ├── public/
│   ├── src/
│   │   ├── Observer/
│   │   │   ├── ObserverInterface.php
│   │   │   ├── NewsChannel.php
│   │   │   ├── EmailNotifier.php
│   │   │   └── SmsNotifier.php
│   │   ├── Subject/
│   │   │   ├── SubjectInterface.php
│   │   │   ├── NewsAgency.php
│   │   │   └── UserEventManager.php
│   │   └── Service/
│   │       └── EventService.php
│   ├── tests/
│   ├── composer.json
│   └── server.php
├── frontend-react/
│   ├── src/
│   │   ├── components/
│   │   │   ├── EventDashboard.jsx
│   │   │   └── ObserverList.jsx
│   │   └── services/
│   │       └── observerService.js
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
- `GET /api/observers` - List registered observers
- `POST /api/subjects/news` - Publish news (notify observers)
- `POST /api/observers/register` - Register new observer
- `DELETE /api/observers/{id}` - Remove observer
- `GET /api/events/history` - Get event history

## Evaluation Criteria
- Proper implementation of Observer pattern interfaces
- Effective decoupling between subjects and observers
- Robust observer management (registration, removal)
- Clean and maintainable code structure
- Comprehensive test coverage
- Documentation quality

## Resources
- [Observer Pattern](https://en.wikipedia.org/wiki/Observer_pattern)
- [PHP Design Patterns](https://designpatternsphp.readthedocs.io/en/latest/Behavioral/Observer/README.html)
- [Event-Driven Architecture](https://en.wikipedia.org/wiki/Event-driven_architecture)