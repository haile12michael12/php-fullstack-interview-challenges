# Challenge 05: gRPC Chat Application

## Description
This challenge focuses on building a real-time chat application using gRPC for communication between clients and server. You'll learn how to implement bidirectional streaming, manage chat rooms and user sessions, handle message broadcasting, and create a responsive chat interface. The challenge covers advanced gRPC features, real-time communication patterns, and scalable chat architecture.

## Learning Objectives
- Implement gRPC bidirectional streaming for real-time communication
- Design chat room and user session management
- Handle message broadcasting and delivery guarantees
- Create responsive chat user interfaces
- Implement user presence and status tracking
- Apply security best practices for chat applications
- Optimize performance for concurrent connections

## Requirements
- PHP 8.1+
- Composer
- Node.js 16+
- Protocol Buffer Compiler (protoc)
- gRPC PHP extension
- Docker (optional, for containerized deployment)
- Understanding of gRPC and streaming concepts

## Features to Implement
1. **gRPC Chat Service**
   - Bidirectional streaming for real-time messaging
   - Chat room management and creation
   - User authentication and session handling
   - Message persistence and history
   - Presence and status notifications
   
2. **Chat Functionality**
   - Private and group messaging
   - Message typing indicators
   - Message read receipts
   - File and media sharing
   - Message reactions and emojis
   
3. **User Management**
   - User registration and authentication
   - Online/offline status tracking
   - User profiles and avatars
   - Contact lists and friend management
   - Notification settings
   
4. **Performance and Scalability**
   - Connection pooling and management
   - Message queuing for high load
   - Horizontal scaling strategies
   - Load balancing for gRPC services
   - Caching for user and room data

## Project Structure
```
challenge-05-grpc-chat/
├── backend-php/
│   ├── src/
│   │   ├── Chat/
│   │   │   ├── ChatService.php
│   │   │   ├── RoomManager.php
│   │   │   ├── UserManager.php
│   │   │   └── MessageService.php
│   │   ├── Proto/
│   │   │   ├── chat.proto
│   │   │   ├── ChatServiceClient.php
│   │   │   └── ChatServiceInterface.php
│   │   └── Exception/
│   │       ├── ChatException.php
│   │       └── AuthenticationException.php
│   ├── public/
│   │   └── index.php
│   ├── config/
│   ├── tests/
│   ├── composer.json
│   └── Dockerfile
├── frontend-react/
│   ├── src/
│   │   ├── components/
│   │   │   ├── ChatRoom.jsx
│   │   │   ├── MessageList.jsx
│   │   │   ├── UserList.jsx
│   │   │   └── ChatInterface.jsx
│   │   ├── services/
│   │   │   └── chatService.js
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
- Protocol Buffer Compiler (protoc)
- gRPC PHP extension
- Docker (optional)

### Backend Setup
1. Navigate to the `backend-php` directory
2. Run `composer install` to install dependencies
3. Generate gRPC code from proto files:
   ```bash
   protoc --php_out=src/ --grpc_out=src/ --plugin=protoc-gen-grpc=/path/to/grpc_php_plugin proto/chat.proto
   ```
4. Start the gRPC server:
   ```bash
   php public/index.php
   ```

### Frontend Setup
1. Navigate to the `frontend-react` directory
2. Run `npm install` to install dependencies
3. Run `npm run dev` to start the development server

### Docker Setup
1. From the challenge root directory, run:
   ```bash
   docker-compose up -d
   ```
2. Access the application at `http://localhost:3000`

## gRPC Service Methods
- `ConnectUser` - Stream for user connection and messages
- `SendMessage` - Send message to chat room
- `JoinRoom` - Join a chat room
- `LeaveRoom` - Leave a chat room
- `GetUsers` - Get list of users in room
- `GetMessages` - Get message history for room

## Evaluation Criteria
- [ ] Proper gRPC bidirectional streaming implementation
- [ ] Effective chat room and user management
- [ ] Secure authentication and authorization
- [ ] Responsive and user-friendly interface
- [ ] Performance optimization for real-time communication
- [ ] Code quality and documentation
- [ ] Test coverage for core functionality

## Resources
- [gRPC Documentation](https://grpc.io/docs/)
- [gRPC Streaming](https://grpc.io/docs/what-is-grpc/core-concepts/#server-streaming-rpc)
- [Protocol Buffers](https://developers.google.com/protocol-buffers)
- [Real-time Chat Architecture](https://www.ably.io/topic/chat-app-architecture)