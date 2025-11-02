# Challenge 14: WebSocket Real-time Dashboard

## Description
This challenge focuses on building a real-time dashboard using WebSockets with PHP backend and React frontend. You'll learn how to implement real-time data streaming, WebSocket server management, and real-time UI updates. The challenge covers WebSocket protocol fundamentals, connection management, data broadcasting, and creating responsive dashboards with live metrics visualization.

## Learning Objectives
- Understanding WebSocket protocol and implementation
- Building real-time data streaming applications
- Managing WebSocket connections and client states
- Implementing real-time UI updates in React
- Working with Chart.js for live data visualization
- Handling error recovery and connection reestablishment
- Optimizing WebSocket performance for multiple clients
- Applying security best practices for WebSocket connections

## Requirements
- PHP 8.1+
- Composer
- Node.js 16+
- Ratchet for WebSocket support
- MySQL database
- Understanding of WebSocket protocol
- Knowledge of real-time data visualization
- Docker (optional, for containerized deployment)

## Features to Implement
1. **WebSocket Server**
   - Connection establishment and handshake
   - Client authentication and authorization
   - Message routing and broadcasting
   - Connection lifecycle management
   - Error handling and recovery mechanisms
   
2. **Real-time Data Streaming**
   - Live metrics collection and processing
   - Data broadcasting to connected clients
   - Message queuing for high-volume data
   - Data filtering and client-specific streams
   - Stream throttling and rate limiting
   
3. **Dashboard Interface**
   - Real-time charts and graphs
   - Live metrics display
   - Interactive dashboard controls
   - Responsive layout for all devices
   - Connection status indicators
   
4. **Performance and Security**
   - Connection pooling and resource management
   - Message compression for bandwidth optimization
   - Secure WebSocket connections (WSS)
   - Client authentication and session management
   - Load testing and performance monitoring

## Project Structure
```
challenge-14-websocket-dashboard/
├── backend-php/
│   ├── src/
│   │   ├── WebSocket/
│   │   │   ├── WebSocketServer.php
│   │   │   ├── ConnectionManager.php
│   │   │   ├── MessageRouter.php
│   │   │   └── BroadcastService.php
│   │   ├── Dashboard/
│   │   │   ├── MetricsCollector.php
│   │   │   ├── DataProvider.php
│   │   │   └── AnalyticsService.php
│   │   └── Exception/
│   │       ├── WebSocketException.php
│   │       └── ConnectionException.php
│   ├── public/
│   │   └── index.php
│   ├── config/
│   ├── migrations/
│   ├── tests/
│   ├── composer.json
│   └── Dockerfile
├── frontend-react/
│   ├── src/
│   │   ├── components/
│   │   │   ├── Dashboard.jsx
│   │   │   ├── MetricsChart.jsx
│   │   │   ├── ConnectionStatus.jsx
│   │   │   └── ControlPanel.jsx
│   │   ├── hooks/
│   │   │   └── useWebSocket.js
│   │   ├── services/
│   │   │   └── dashboardService.js
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
3. Copy `.env.example` to `.env` and configure your settings
4. Start the WebSocket server:
   ```bash
   php public/index.php
   ```

### Frontend Setup
1. Navigate to the `frontend-react` directory
2. Run `npm install` to install frontend dependencies
3. Run `npm run dev` to start the development server

### Docker Setup
1. From the challenge root directory, run:
   ```bash
   docker-compose up -d
   ```
2. Access the application at `http://localhost:3000`

## WebSocket Protocol

### Connection Endpoints
- `ws://localhost:8080/dashboard` - Main dashboard connection
- `ws://localhost:8080/metrics` - Metrics streaming
- `ws://localhost:8080/notifications` - Notification system

### Message Format
```json
{
  "type": "metrics_update",
  "data": {
    "timestamp": "2023-01-01T12:00:00Z",
    "metrics": {
      "cpu_usage": 45.2,
      "memory_usage": 67.8,
      "network_traffic": 1250,
      "active_connections": 24
    }
  },
  "correlation_id": "abc123"
}
```

### Supported Message Types
- `connection_ack` - Connection established acknowledgment
- `metrics_update` - Real-time metrics data
- `notification` - System notifications
- `error` - Error messages and codes
- `ping/pong` - Connection keep-alive messages

## Evaluation Criteria
- [ ] Robust WebSocket server implementation
- [ ] Efficient real-time data streaming
- [ ] Proper connection management and error handling
- [ ] Responsive and interactive dashboard interface
- [ ] Performance optimization for multiple clients
- [ ] Security implementation for WebSocket connections
- [ ] Code quality and documentation
- [ ] Test coverage for WebSocket functionality

## Resources
- [WebSocket Protocol RFC](https://tools.ietf.org/html/rfc6455)
- [Ratchet PHP WebSocket Library](http://socketo.me/)
- [Chart.js Documentation](https://www.chartjs.org/docs/latest/)
- [Real-time Web Applications](https://developer.mozilla.org/en-US/docs/Web/API/WebSockets_API)
- [WebSocket Security Considerations](https://developer.mozilla.org/en-US/docs/Web/API/WebSockets_API/Writing_WebSocket_servers#security_considerations)