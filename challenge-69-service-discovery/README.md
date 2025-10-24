# Challenge 69: Service Discovery

## Description
In this challenge, you'll implement a service discovery system that allows services to dynamically find and communicate with each other in a distributed environment. Service discovery is crucial for building resilient microservices architectures.

## Learning Objectives
- Understand service discovery patterns and benefits
- Implement service registration and deregistration
- Create service discovery mechanisms
- Handle service health monitoring
- Manage service metadata and tags
- Implement client-side and server-side discovery

## Requirements
Create a service discovery system with the following features:

1. **Service Registry**:
   - Service registration with metadata
   - Service deregistration
   - Service health status tracking
   - Service metadata and tags
   - TTL-based service expiration
   - Persistent storage integration

2. **Discovery Mechanisms**:
   - Client-side service discovery
   - Server-side service discovery
   - Service lookup by name and tags
   - Load balancing integration
   - Caching for improved performance
   - Fallback mechanisms

3. **Health Monitoring**:
   - Active health checks
   - Passive health checks
   - Health status aggregation
   - Failure detection and recovery
   - Graceful degradation
   - Alerting and notifications

4. **Advanced Features**:
   - Service versioning support
   - Multi-datacenter support
   - Security and authentication
   - Service mesh integration
   - Configuration distribution
   - Event-driven notifications

## Features to Implement
- [ ] Service registration and deregistration
- [ ] Service discovery by name and tags
- [ ] Health check monitoring
- [ ] TTL-based service expiration
- [ ] Client-side discovery
- [ ] Server-side discovery
- [ ] Load balancing integration
- [ ] Service metadata management
- [ ] Caching for performance
- [ ] Fallback mechanisms
- [ ] Event-driven notifications

## Project Structure
```
backend-php/
├── src/
│   ├── Discovery/
│   │   ├── ServiceRegistry.php
│   │   ├── ServiceDiscovery.php
│   │   ├── ServiceInstance.php
│   │   ├── HealthChecker.php
│   │   ├── LoadBalancer.php
│   │   ├── Cache/
│   │   │   └── DiscoveryCache.php
│   │   └── Events/
│   │       ├── ServiceRegisteredEvent.php
│   │       ├── ServiceDeregisteredEvent.php
│   │       └── ServiceHealthChangedEvent.php
│   ├── Http/
│   │   ├── Request.php
│   │   ├── Response.php
│   │   └── HttpClient.php
│   └── Services/
│       ├── UserService.php
│       ├── PostService.php
│       └── NotificationService.php
├── public/
│   └── index.php
├── storage/
│   └── registry.json
├── config/
│   └── discovery.php
└── vendor/

frontend-react/
├── src/
│   ├── api/
│   │   └── discovery.js
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

## Service Registration Example
```json
{
  "service": {
    "id": "user-service-1",
    "name": "user-service",
    "address": "192.168.1.100",
    "port": 8080,
    "tags": ["primary", "v1.2"],
    "meta": {
      "version": "1.2.3",
      "environment": "production"
    },
    "checks": [
      {
        "http": "http://192.168.1.100:8080/health",
        "interval": "10s",
        "timeout": "1s"
      }
    ]
  }
}
```

## API Endpoints
```
# Service Registration
PUT    /v1/agent/service/register
DELETE /v1/agent/service/deregister/{serviceId}

# Service Discovery
GET    /v1/catalog/service/{serviceName}
GET    /v1/health/service/{serviceName}
GET    /v1/catalog/services

# Health Checks
GET    /v1/health/checks/{serviceId}
PUT    /v1/agent/check/pass/{checkId}
PUT    /v1/agent/check/fail/{checkId}

# Service Management
GET    /v1/agent/services
GET    /v1/agent/self
```

## Service Discovery Patterns

### Client-Side Discovery
```
Client -> Service Registry -> Get Service Instances -> Call Service Directly
```

### Server-Side Discovery
```
Client -> Load Balancer/API Gateway -> Service Registry -> Route to Service
```

## Example Service Metadata
```json
{
  "id": "post-service-2",
  "name": "post-service",
  "address": "192.168.1.101",
  "port": 8081,
  "tags": ["secondary", "v1.1", "backup"],
  "meta": {
    "version": "1.1.5",
    "environment": "production",
    "region": "us-west-1"
  },
  "status": "passing"
}
```

## Evaluation Criteria
- [ ] Service registration works correctly
- [ ] Service discovery finds registered services
- [ ] Health checks monitor service status
- [ ] TTL expiration removes stale services
- [ ] Client-side discovery functions
- [ ] Server-side discovery routes requests
- [ ] Load balancing distributes traffic
- [ ] Caching improves performance
- [ ] Fallback mechanisms handle failures
- [ ] Event notifications work
- [ ] Code is well-organized and documented
- [ ] Tests cover discovery functionality

## Resources
- [Service Discovery Pattern](https://microservices.io/patterns/client-side-discovery.html)
- [Consul Service Discovery](https://www.consul.io/docs/discovery)
- [Eureka Service Discovery](https://github.com/Netflix/eureka)
- [Kubernetes Service Discovery](https://kubernetes.io/docs/concepts/services-networking/service/)