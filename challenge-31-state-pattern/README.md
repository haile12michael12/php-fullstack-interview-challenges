# Challenge 31: State Pattern

## Description
This challenge focuses on implementing the State design pattern to create an order processing workflow with different states. You'll learn to encapsulate state-specific behavior and allow objects to change their behavior when their state changes.

## Learning Objectives
- Implement the State design pattern
- Encapsulate state-specific behavior
- Allow objects to change behavior based on state
- Eliminate complex conditional logic
- Create extensible state machines
- Understand state transitions and context

## Requirements
- PHP 8.1+
- Composer
- Understanding of design patterns
- Knowledge of workflow and state machine concepts

## Features to Implement
1. State Interface
   - State transition methods
   - Context access
   - State-specific behavior

2. Concrete States
   - Pending state
   - Processing state
   - Shipped state
   - Delivered state
   - Cancelled state
   - Returned state

3. Context Class
   - Order management
   - State transitions
   - State history tracking

4. Advanced Features
   - State validation
   - Transition rules
   - State persistence
   - Audit logging

## Project Structure
```
challenge-31-state-pattern/
├── backend-php/
│   ├── config/
│   ├── public/
│   ├── src/
│   │   ├── State/
│   │   │   ├── OrderStateInterface.php
│   │   │   ├── PendingState.php
│   │   │   ├── ProcessingState.php
│   │   │   ├── ShippedState.php
│   │   │   ├── DeliveredState.php
│   │   │   ├── CancelledState.php
│   │   │   └── ReturnedState.php
│   │   ├── Context/
│   │   │   ├── OrderContext.php
│   │   │   └── StateHistory.php
│   │   └── Service/
│   │       └── OrderService.php
│   ├── tests/
│   ├── composer.json
│   └── server.php
├── frontend-react/
│   ├── src/
│   │   ├── components/
│   │   │   ├── OrderWorkflow.jsx
│   │   │   └── StateVisualizer.jsx
│   │   └── services/
│   │       └── orderService.js
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
- `GET /api/orders/{id}` - Get order with current state
- `POST /api/orders/{id}/process` - Process order (state transition)
- `POST /api/orders/{id}/ship` - Ship order (state transition)
- `POST /api/orders/{id}/deliver` - Deliver order (state transition)
- `POST /api/orders/{id}/cancel` - Cancel order (state transition)
- `GET /api/orders/{id}/history` - Get state transition history

## Evaluation Criteria
- Proper implementation of State pattern interfaces
- Effective state transition management
- Clean encapsulation of state-specific behavior
- Robust state validation and rules
- Comprehensive audit logging
- Comprehensive test coverage

## Resources
- [State Pattern](https://en.wikipedia.org/wiki/State_pattern)
- [PHP Design Patterns](https://designpatternsphp.readthedocs.io/en/latest/Behavioral/State/README.html)
- [Finite State Machines](https://en.wikipedia.org/wiki/Finite-state_machine)