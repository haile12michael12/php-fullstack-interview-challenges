# Challenge 03: Long Polling Notification System

## Description
This challenge focuses on implementing a real-time notification system using long polling techniques. Long polling is a method where the client requests information from the server, and the server holds the request open until new information is available. You'll learn how to implement efficient long polling mechanisms, handle connection timeouts, and manage client-server communication for real-time updates.

## Learning Objectives
- Understand long polling communication patterns
- Implement efficient long polling mechanisms in PHP
- Handle connection timeouts and client disconnections
- Manage concurrent connections and resource usage
- Create a notification system with real-time updates
- Implement proper error handling and recovery
- Optimize server resources for long-running connections

## Requirements
- PHP 8.1+
- Composer
- Node.js 16+
- Understanding of HTTP protocol and server-side events
- Knowledge of asynchronous programming concepts
- Docker (optional, for containerized deployment)

## Features to Implement
1. **Long Polling Server**
   - HTTP endpoint for long polling requests
   - Connection timeout handling
   - Message queuing system
   - Client connection management
   
2. **Notification System**
   - Message broadcasting to connected clients
   - Notification types and categories
   - Message persistence and delivery guarantees
   - Client subscription management
   
3. **Client-Side Implementation**
   - JavaScript long polling client
   - Connection state management
   - Message display and UI updates
   - Error handling and reconnection logic
   
4. **Resource Management**
   - Connection pooling
   - Memory usage optimization
   - Graceful shutdown handling
   - Load testing and performance monitoring

## Project Structure
```
challenge-03-long-polling-notify/
├── backend-php/
│   ├── src/
│   │   ├── LongPolling/
│   │   │   ├── Server.php
│   │   │   ├── ConnectionManager.php
│   │   │   └── MessageQueue.php
│   │   ├── Notification/
│   │   │   ├── NotificationService.php
│   │   │   ├── Message.php
│   │   │   └── SubscriptionManager.php
│   │   └── Exception/
│   │       ├── TimeoutException.php
│   │       └── ConnectionException.php
│   ├── public/
│   │   └── index.php
│   ├── config/
│   ├── tests/
│   ├── composer.json
│   └── Dockerfile
├── frontend-react/
│   ├── src/
│   │   ├── components/
│   │   │   ├── NotificationPanel.jsx
│   │   │   ├── ConnectionStatus.jsx
│   │   │   └── MessageList.jsx
│   │   ├── services/
│   │   │   └── longPollingService.js
│   │   ├── App.jsx
│   │   └── main.jsx
│   ├── package.json
│   ├── vite.config.js
│   └── Dockerfile
├── docker-compose.yml
└── README.md
```

## Setup Instructions

### Prerequisites
- PHP 8.1+
- Composer
- Node.js 16+
- Docker (optional)

### Backend Setup
1. Navigate to the `backend-php` directory
2. Run `composer install` to install dependencies
3. Start the development server with `php public/index.php`

### Frontend Setup
1. Navigate to the `frontend-react` directory
2. Run `npm install` to install dependencies
3. Run `npm run dev` to start the development server

### Docker Setup
1. From the challenge root directory, run `docker-compose up -d`
2. Access the application at `http://localhost:3000`

## API Endpoints
- `GET /api/poll` - Long polling endpoint for notifications
- `POST /api/notify` - Send notification to clients
- `GET /api/status` - Server status and connection count
- `POST /api/subscribe` - Subscribe to notification categories

## Evaluation Criteria
- [ ] Long polling implementation works correctly
- [ ] Proper timeout and error handling
- [ ] Efficient resource usage and connection management
- [ ] Notification system delivers messages reliably
- [ ] Client-side implementation handles reconnections
- [ ] Code is well-organized and documented
- [ ] Tests cover core functionality

## Resources
- [Long Polling vs WebSockets](https://www.pubnub.com/blog/websockets-vs-long-polling/)
- [PHP Asynchronous Programming](https://www.php.net/manual/en/book.swoole.php)
- [HTTP Server-Sent Events](https://developer.mozilla.org/en-US/docs/Web/API/Server-sent_events)
- [Real-time Web Applications](https://developer.mozilla.org/en-US/docs/Web/API/Server-sent_events/Using_server-sent_events)