# Challenge 10: Docker Microservices

## Description
This challenge focuses on designing and implementing a microservices architecture using Docker. You'll learn how to break down a monolithic application into smaller, independent services, configure inter-service communication, manage data consistency across services, and deploy a complete microservices ecosystem using Docker Compose.

## Learning Objectives
- Understand microservices architecture principles
- Design service boundaries and responsibilities
- Implement inter-service communication patterns
- Configure Docker for multi-service applications
- Manage data consistency in distributed systems
- Implement service discovery and load balancing
- Apply DevOps practices for microservices deployment

## Requirements
- PHP 8.1+
- Composer
- Node.js 16+
- Docker and Docker Compose
- Understanding of REST APIs and HTTP communication
- Knowledge of database design principles
- Familiarity with containerization concepts

## Features to Implement
1. **Service Architecture**
   - Independent service design
   - API gateway pattern
   - Service registry and discovery
   - Load balancing between services
   
2. **Inter-Service Communication**
   - REST API communication
   - Message queue integration
   - Event-driven architecture
   - Circuit breaker pattern
   
3. **Data Management**
   - Database per service pattern
   - Distributed transaction handling
   - Data consistency strategies
   - Backup and recovery mechanisms
   
4. **Deployment and Operations**
   - Docker Compose configuration
   - Health checks and monitoring
   - Logging aggregation
   - Scaling and resource management

## Project Structure
```
challenge-10-docker-microservices/
├── services/
│   ├── user-service/
│   │   ├── src/
│   │   ├── Dockerfile
│   │   └── composer.json
│   ├── product-service/
│   │   ├── src/
│   │   ├── Dockerfile
│   │   └── composer.json
│   ├── order-service/
│   │   ├── src/
│   │   ├── Dockerfile
│   │   └── composer.json
│   └── api-gateway/
│       ├── src/
│       ├── Dockerfile
│       └── nginx.conf
├── infrastructure/
│   ├── database/
│   ├── message-queue/
│   └── monitoring/
├── docker-compose.yml
└── README.md
```

## Setup Instructions

### Prerequisites
- Docker and Docker Compose
- PHP 8.1+ (for local development)
- Node.js 16+ (for frontend tools)
- Git

### Running with Docker Compose
1. From the challenge root directory, run:
   ```bash
   docker-compose up -d
   ```
2. Access the application at `http://localhost:8080`

### Individual Service Development
1. Navigate to any service directory
2. Run `composer install` to install dependencies
3. Start the service with its specific startup command

### Monitoring and Management
1. Check service status:
   ```bash
   docker-compose ps
   ```
2. View service logs:
   ```bash
   docker-compose logs [service-name]
   ```
3. Scale services:
   ```bash
   docker-compose up -d --scale user-service=3
   ```

## Services Overview
- **User Service**: Manages user accounts, authentication, and profiles
- **Product Service**: Handles product catalog and inventory
- **Order Service**: Manages customer orders and transactions
- **API Gateway**: Routes requests to appropriate services

## Evaluation Criteria
- [ ] Well-designed microservices architecture
- [ ] Proper service boundaries and responsibilities
- [ ] Effective inter-service communication
- [ ] Data consistency and management strategies
- [ ] Docker configuration and deployment
- [ ] Monitoring and operational capabilities
- [ ] Code quality and documentation

## Resources
- [Microservices Architecture](https://microservices.io/)
- [Docker Documentation](https://docs.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)
- [12-Factor App](https://12factor.net/)
- [Service Mesh](https://servicemesh.io/)