# Challenge 93: Server-Sent Events

## Description
In this challenge, you'll implement Server-Sent Events (SSE) to enable one-way real-time communication from server to client. SSE is ideal for applications requiring continuous updates from the server such as live feeds, notifications, stock prices, or system monitoring. You'll build a robust SSE implementation that handles connections efficiently, manages reconnections, and provides a seamless user experience.

## Learning Objectives
- Understand Server-Sent Events protocol and use cases
- Implement SSE endpoints in PHP
- Handle client reconnections and event IDs
- Manage connection lifecycle and resource cleanup
- Create event streams with proper formatting
- Implement retry mechanisms and error handling
- Build scalable SSE architectures
- Compare SSE with WebSockets and polling

## Requirements

### Core Features
1. **SSE Endpoint Implementation**
   - Create HTTP endpoints that stream events using text/event-stream
   - Implement proper HTTP headers (Content-Type, Cache-Control, Connection)
   - Handle multiple concurrent client connections
   - Support event IDs for reconnection continuity

2. **Event Stream Management**
   - Generate and send structured events with data, event type, and ID
   - Implement periodic heartbeat messages
   - Handle client disconnections gracefully
   - Support custom event types and filtering

3. **Reconnection Handling**
   - Implement automatic client reconnection
   - Use last-event-id for state recovery
   - Configure retry intervals
   - Handle network interruptions gracefully

4. **Data Broadcasting**
   - Send real-time updates to connected clients
   - Support channel-based event distribution
   - Implement message queuing for disconnected clients
   - Handle backpressure and client readiness

5. **Resource Management**
   - Efficiently manage memory for active connections
   - Implement connection timeouts and cleanup
   - Monitor resource usage and connection limits
   - Handle server shutdown gracefully

### Advanced Features
1. **Scalability Considerations**
   - Implement connection pooling
   - Support horizontal scaling with Redis or similar
   - Handle load balancing scenarios
   - Optimize for thousands of concurrent connections

2. **Security Measures**
   - Implement authentication and authorization
   - Add CORS headers and origin validation
   - Prevent denial-of-service attacks
   - Sanitize and validate event data

3. **Monitoring and Metrics**
   - Track active connections and event throughput
   - Monitor connection lifecycle events
   - Log errors and exceptions
   - Expose metrics for observability

## Project Structure
```
challenge-93-server-sent-events/
├── backend-php/
│   ├── src/
│   │   ├── SSE/
│   │   │   ├── EventStream.php
│   │   │   ├── ConnectionManager.php
│   │   │   ├── Event.php
│   │   │   └── Exception/
│   │   │       ├── SSEException.php
│   │   │       └── ConnectionException.php
│   │   └── Handlers/
│   │       ├── NotificationHandler.php
│   │       └── MetricsHandler.php
│   ├── config/
│   ├── public/
│   │   └── sse-endpoint.php
│   ├── tests/
│   ├── composer.json
│   ├── Dockerfile
│   └── README.md
├── frontend-react/
│   ├── src/
│   │   ├── components/
│   │   │   ├── EventFeed.jsx
│   │   │   ├── ConnectionStatus.jsx
│   │   │   └── EventList.jsx
│   │   ├── hooks/
│   │   │   └── useEventSource.js
│   │   └── App.jsx
│   ├── package.json
│   ├── vite.config.js
│   ├── Dockerfile
│   └── README.md
├── docker-compose.yml
└── README.md
```

## Setup Instructions

### Prerequisites
- PHP 8.1+
- Composer
- Node.js 16+
- npm or yarn
- Docker (optional, for containerized deployment)

### Backend Setup
1. Navigate to the [backend-php](file:///c%3A/projects/php-fullstack-challenges/challenge-93-server-sent-events/backend-php) directory
2. Install PHP dependencies:
   ```bash
   composer install
   ```
3. Start the SSE server:
   ```bash
   php public/sse-endpoint.php
   ```

### Frontend Setup
1. Navigate to the [frontend-react](file:///c%3A/projects/php-fullstack-challenges/challenge-93-server-sent-events/frontend-react) directory
2. Install JavaScript dependencies:
   ```bash
   npm install
   ```
3. Start the development server:
   ```bash
   npm run dev
   ```

### Docker Setup
1. From the challenge root directory, run:
   ```bash
   docker-compose up -d
   ```
2. Access the application at `http://localhost:3000`

## API Endpoints

### SSE Endpoint
- **URL**: `http://localhost:8080/events`
- **Method**: GET
- **Content-Type**: text/event-stream
- **Headers**: 
  - `Cache-Control: no-cache`
  - `Connection: keep-alive`
  - `Access-Control-Allow-Origin: *`

### HTTP Management Endpoints
- **GET** `/stats` - Get server statistics
- **POST** `/broadcast` - Send event to all connected clients
- **GET** `/clients` - List all connected clients

## Implementation Details

### Event Stream Format
The SSE implementation should follow the standard event stream format:
```
id: 12345
event: message
data: {"user": "john", "text": "Hello world!"}
data: {"timestamp": "2023-01-01T12:00:00Z"}

id: 12346
event: notification
data: {"title": "New Message", "body": "You have a new message"}

```

### Backend Implementation
The PHP backend should:
1. Set appropriate HTTP headers for SSE
2. Disable output buffering
3. Handle client disconnections
4. Send periodic heartbeat messages
5. Manage event IDs for reconnection
6. Implement proper error handling

### Frontend Client
The React frontend should:
1. Use EventSource API to connect to SSE endpoint
2. Handle connection states (connecting, open, closed)
3. Process different event types
4. Implement reconnection logic
5. Display real-time events in UI

### Connection Management
The server should:
1. Track active connections with unique identifiers
2. Handle connection establishment and termination
3. Clean up resources when clients disconnect
4. Limit concurrent connections to prevent resource exhaustion
5. Implement graceful shutdown procedures

## Evaluation Criteria
1. **Correctness** (30%)
   - Proper SSE protocol implementation
   - Correct event stream formatting
   - Accurate handling of connection lifecycle

2. **Robustness** (25%)
   - Error handling and recovery
   - Graceful degradation
   - Resource cleanup and memory management

3. **Scalability** (20%)
   - Efficient handling of concurrent connections
   - Memory and CPU usage optimization
   - Horizontal scaling considerations

4. **User Experience** (15%)
   - Smooth reconnection handling
   - Proper error messaging
   - Responsive UI updates

5. **Code Quality** (10%)
   - Clean, well-organized code
   - Proper documentation and comments
   - Following PHP and React best practices

## Resources
1. [Server-Sent Events Specification](https://html.spec.whatwg.org/multipage/server-sent-events.html)
2. [MDN Server-Sent Events Guide](https://developer.mozilla.org/en-US/docs/Web/API/Server-sent_events)
3. [React Documentation](https://reactjs.org/docs/getting-started.html)
4. [PHP Output Control Functions](https://www.php.net/manual/en/book.outcontrol.php)
5. [EventSource API](https://developer.mozilla.org/en-US/docs/Web/API/EventSource)
6. [Comparing SSE with WebSockets](https://www.smashingmagazine.com/2018/02/sse-websockets-data-flow-http2/)

## Stretch Goals
1. Implement event filtering and subscription channels
2. Add support for binary data transmission
3. Create a SSE proxy/load balancer
4. Implement message persistence with database storage
5. Add compression for event streams
6. Create client SDKs for different languages