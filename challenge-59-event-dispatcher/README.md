# Challenge 59: Event Dispatcher

## Description
In this challenge, you'll build an event dispatcher system that allows loose coupling between components through events. This pattern is widely used in frameworks and applications to enable extensibility and decoupling.

## Learning Objectives
- Understand the Observer pattern and event-driven architecture
- Implement event subscription and dispatching
- Create event listeners and subscribers
- Handle event propagation and stopping
- Implement priority-based event handling
- Support wildcard event listening

## Requirements
Create an event dispatcher system with the following features:

1. **Event System**:
   - Event objects with name and data payload
   - Event inheritance and hierarchical naming
   - Immutable event objects
   - Event metadata and timestamps

2. **Dispatcher Functionality**:
   - Subscribe listeners to specific events
   - Dispatch events to all subscribed listeners
   - Priority-based listener execution
   - Wildcard event matching
   - Stop event propagation

3. **Listener Management**:
   - Closure-based listeners
   - Class-based listeners with method callbacks
   - Subscriber interfaces for bulk event registration
   - Listener removal and cleanup

4. **Advanced Features**:
   - Event buffering and queuing
   - Conditional event dispatching
   - Event logging and debugging
   - Asynchronous event handling
   - Event middleware processing

## Features to Implement
- [ ] Event class with name and payload
- [ ] Event dispatcher with subscribe/dispatch methods
- [ ] Priority-based listener execution
- [ ] Wildcard event matching (e.g., `user.*`)
- [ ] Event propagation control
- [ ] Subscriber interface for bulk registration
- [ ] Event buffering and queuing
- [ ] Conditional event dispatching
- [ ] Event middleware support
- [ ] Comprehensive error handling

## Project Structure
```
backend-php/
├── src/
│   ├── Event/
│   │   ├── Event.php
│   │   ├── EventDispatcher.php
│   │   ├── EventDispatcherInterface.php
│   │   ├── EventSubscriberInterface.php
│   │   └── StoppableEventInterface.php
│   ├── Listeners/
│   │   ├── UserRegisteredListener.php
│   │   ├── EmailNotificationListener.php
│   │   └── LogActivityListener.php
│   ├── Subscribers/
│   │   └── UserEventSubscriber.php
│   └── Middleware/
│       └── EventMiddleware.php
├── public/
│   └── index.php
├── config/
│   └── events.php
└── vendor/

frontend-react/
├── src/
│   ├── components/
│   ├── App.jsx
│   └── main.jsx
├── public/
└── package.json
```

## Setup Instructions
1. Navigate to the `backend-php` directory
2. Run `composer install` to install dependencies
3. Configure your web server to point to the `public` directory
4. Start the development server with `php -S localhost:8000 -t public`
5. Navigate to the `frontend-react` directory
6. Run `npm install` to install dependencies
7. Start the development server with `npm run dev`

## API Endpoints
- `POST /events/dispatch` - Dispatch a new event
- `GET /events/listeners` - List all registered listeners
- `POST /events/subscribe` - Subscribe a new listener
- `POST /events/unsubscribe` - Unsubscribe a listener

## Example Events
- `user.registered` - Triggered when a user registers
- `order.created` - Triggered when an order is created
- `payment.processed` - Triggered when a payment is processed
- `email.sent` - Triggered when an email is sent

## Evaluation Criteria
- [ ] Events are properly dispatched to listeners
- [ ] Priority-based execution works correctly
- [ ] Wildcard event matching functions
- [ ] Event propagation can be stopped
- [ ] Subscriber interface registers multiple events
- [ ] Error handling is comprehensive
- [ ] Event middleware is supported
- [ ] Code is well-organized and documented
- [ ] Tests cover event system functionality

## Resources
- [Observer Pattern](https://en.wikipedia.org/wiki/Observer_pattern)
- [Symfony Event Dispatcher](https://symfony.com/doc/current/components/event_dispatcher.html)
- [Laravel Events](https://laravel.com/docs/events)
- [Event-Driven Architecture](https://martinfowler.com/articles/201701-event-driven.html)