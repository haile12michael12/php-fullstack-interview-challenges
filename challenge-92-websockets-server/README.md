# Challenge 92: WebSockets Server

## Description
In this challenge, you'll build a WebSocket server to enable real-time, bidirectional communication between clients and the server. WebSockets are essential for applications requiring live updates such as chat systems, real-time dashboards, gaming, and collaborative tools. You'll implement a robust WebSocket server that handles connections, messages, and disconnections efficiently while maintaining scalability.

## Learning Objectives
- Understand WebSocket protocol and handshake process
- Implement a WebSocket server from scratch
- Handle connection lifecycle events
- Manage concurrent connections and broadcasting
- Implement message framing and parsing
- Create heartbeat mechanisms for connection health
- Build error handling and recovery strategies
- Design scalable WebSocket architectures

## Requirements

### Core Features
1. **WebSocket Handshake**
   - Implement HTTP to WebSocket upgrade mechanism
   - Validate Sec-WebSocket-Key and generate Sec-WebSocket-Accept
   - Handle proper HTTP response codes and headers
   - Support WebSocket version 13 (RFC 6455)

2. **Connection Management**
   - Track active connections with unique identifiers
   - Handle connection establishment and termination
   - Implement connection timeouts and cleanup
   - Support graceful shutdown procedures

3. **Message Handling**
   - Parse incoming WebSocket frames (text and binary)
   - Handle message fragmentation and reassembly
   - Implement proper encoding and decoding
   - Support different opcodes (continuation, text, binary, close, ping, pong)

4. **Broadcasting System**
   - Send messages to all connected clients
   - Send messages to specific clients or groups
   - Implement room/channel-based messaging
   - Handle message queuing for disconnected clients

5. **Heartbeat and Health Checks**
   - Implement ping/pong mechanism
   - Detect stale connections and clean them up
   - Monitor connection health and uptime
   - Handle automatic reconnection logic

6. **Error Handling**
   - Gracefully handle malformed frames
   - Implement proper error codes and close reasons
   - Log errors and exceptions appropriately
   - Recover from connection failures

### Advanced Features
1. **Scalability Considerations**
   - Implement connection pooling
   - Support horizontal scaling with Redis or similar
   - Handle load balancing with sticky sessions
   - Optimize memory usage for thousands of connections

2. **Security Measures**
   - Implement origin validation
   - Add authentication and authorization
   - Prevent denial-of-service attacks
   - Sanitize and validate all incoming messages

3. **Monitoring and Metrics**
   - Track connection statistics
   - Monitor message throughput
   - Log connection events and errors
   - Expose metrics for observability

## Project Structure
```
challenge-92-websockets-server/
├── backend-php/
│   ├── src/
│   │   ├── WebSocket/
│   │   │   ├── Server.php
│   │   │   ├── Connection.php
│   │   │   ├── Frame.php
│   │   │   ├── Message.php
│   │   │   └── Exception/
│   │   │       ├── WebSocketException.php
│   │   │       └── ConnectionException.php
│   │   └── Handlers/
│   │       ├── ChatHandler.php
│   │       └── NotificationHandler.php
│   ├── config/
│   ├── public/
│   │   └── websocket-server.php
│   ├── tests/
│   ├── composer.json
│   ├── Dockerfile
│   └── README.md
├── frontend-react/
│   ├── src/
│   │   ├── components/
│   │   │   ├── ChatRoom.jsx
│   │   │   ├── ConnectionStatus.jsx
│   │   │   └── MessageList.jsx
│   │   ├── hooks/
│   │   │   └── useWebSocket.js
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
1. Navigate to the [backend-php](file:///c%3A/projects/php-fullstack-challenges/challenge-92-websockets-server/backend-php) directory
2. Install PHP dependencies:
   ```bash
   composer install
   ```
3. Start the WebSocket server:
   ```bash
   php public/websocket-server.php
   ```

### Frontend Setup
1. Navigate to the [frontend-react](file:///c%3A/projects/php-fullstack-challenges/challenge-92-websockets-server/frontend-react) directory
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

### WebSocket Endpoint
- **URL**: `ws://localhost:8080`
- **Protocol**: RFC 6455 WebSocket
- **Methods**: N/A (WebSocket protocol)

### HTTP Management Endpoints
- **GET** `/stats` - Get server statistics
- **POST** `/broadcast` - Broadcast message to all clients
- **GET** `/clients` - List all connected clients

## Implementation Details

### WebSocket Server
The WebSocket server should be built using PHP's socket functions or a library like Ratchet. It should handle:
1. HTTP handshake conversion to WebSocket
2. Frame parsing and construction
3. Connection lifecycle management
4. Message broadcasting
5. Error handling and logging

### Connection Handler
Each connection should be represented by a Connection object that:
1. Maintains client metadata
2. Handles message sending and receiving
3. Manages connection state
4. Implements heartbeat mechanisms

### Message Protocol
Define a structured message format for communication:
```json
{
  "type": "chat_message",
  "payload": {
    "user": "username",
    "message": "Hello World!",
    "timestamp": "2023-01-01T12:00:00Z"
  },
  "metadata": {
    "room": "general"
  }
}
```

### Frontend Client
The React frontend should:
1. Establish WebSocket connection
2. Display connection status
3. Send and receive messages
4. Handle reconnection logic
5. Provide user interface for interaction

## Evaluation Criteria
1. **Correctness** (30%)
   - Proper WebSocket handshake implementation
   - Accurate frame parsing and construction
   - Correct handling of connection lifecycle

2. **Robustness** (25%)
   - Error handling and recovery
   - Graceful degradation
   - Resource cleanup and memory management

3. **Scalability** (20%)
   - Efficient handling of concurrent connections
   - Memory and CPU usage optimization
   - Horizontal scaling considerations

4. **Security** (15%)
   - Input validation and sanitization
   - Origin checking and authentication
   - Protection against common attacks

5. **Code Quality** (10%)
   - Clean, well-organized code
   - Proper documentation and comments
   - Following PHP and React best practices

## Resources
1. [RFC 6455 - The WebSocket Protocol](https://datatracker.ietf.org/doc/html/rfc6455)
2. [Mozilla WebSocket API Documentation](https://developer.mozilla.org/en-US/docs/Web/API/WebSockets_API)
3. [React Documentation](https://reactjs.org/docs/getting-started.html)
4. [PHP Socket Programming](https://www.php.net/manual/en/book.sockets.php)
5. [Ratchet PHP WebSocket Library](http://socketo.me/)
6. [WebSocket Testing Tools](https://www.websocket.org/echo.html)

## Stretch Goals
1. Implement WebSocket compression extensions
2. Add support for WebSocket subprotocols
3. Create a WebSocket proxy/load balancer
4. Implement message persistence with database storage
5. Add end-to-end encryption for messages
6. Create WebSocket client SDKs for different languages