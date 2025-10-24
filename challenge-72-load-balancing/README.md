# Challenge 72: Load Balancing

## Description
In this challenge, you'll implement various load balancing algorithms to distribute incoming requests across multiple server instances. Load balancing is essential for scaling applications and ensuring high availability.

## Learning Objectives
- Understand load balancing concepts and algorithms
- Implement different load balancing strategies
- Handle server health monitoring
- Manage session persistence
- Implement weighted load balancing
- Create adaptive load balancing

## Requirements
Create a load balancer with the following features:

1. **Load Balancing Algorithms**:
   - Round Robin
   - Weighted Round Robin
   - Least Connections
   - Weighted Least Connections
   - IP Hash
   - Random Selection
   - Least Response Time

2. **Health Monitoring**:
   - Active health checks
   - Passive health checks
   - Health check intervals
   - Failure thresholds
   - Automatic server removal
   - Graceful server reintroduction

3. **Session Management**:
   - Sticky sessions
   - Cookie-based persistence
   - URL parameter persistence
   - Header-based persistence
   - Session affinity
   - Load balancing bypass for sticky sessions

4. **Advanced Features**:
   - Weighted server support
   - Dynamic server registration
   - Configuration reloading
   - Metrics and statistics
   - Rate limiting integration
   - SSL termination

## Features to Implement
- [ ] Round Robin load balancing
- [ ] Weighted Round Robin
- [ ] Least Connections algorithm
- [ ] IP Hash distribution
- [ ] Health check monitoring
- [ ] Server failure detection
- [ ] Sticky session support
- [ ] Weighted server support
- [ ] Dynamic server registration
- [ ] Metrics collection
- [ ] Configuration reloading

## Project Structure
```
backend-php/
├── src/
│   ├── LoadBalancer/
│   │   ├── LoadBalancer.php
│   │   ├── Algorithms/
│   │   │   ├── RoundRobin.php
│   │   │   ├── WeightedRoundRobin.php
│   │   │   ├── LeastConnections.php
│   │   │   ├── IpHash.php
│   │   │   ├── Random.php
│   │   │   └── LeastResponseTime.php
│   │   ├── Health/
│   │   │   ├── HealthChecker.php
│   │   │   ├── ActiveCheck.php
│   │   │   └── PassiveCheck.php
│   │   ├── Persistence/
│   │   │   ├── StickySession.php
│   │   │   ├── CookiePersistence.php
│   │   │   └── HeaderPersistence.php
│   │   └── Server/
│   │       ├── ServerPool.php
│   │       ├── ServerInstance.php
│   │       └── ServerRegistry.php
│   ├── Http/
│   │   ├── Request.php
│   │   ├── Response.php
│   │   └── HttpClient.php
│   └── Services/
├── public/
│   └── index.php
├── storage/
│   └── load-balancer.json
├── config/
│   └── load-balancer.php
└── vendor/

frontend-react/
├── src/
│   ├── api/
│   │   └── load-balancer.js
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

## Load Balancing Algorithms

### Round Robin
Distributes requests sequentially across servers:
```
Request 1 -> Server A
Request 2 -> Server B
Request 3 -> Server C
Request 4 -> Server A
```

### Weighted Round Robin
Considers server weights for distribution:
```
Server A (weight: 3) -> Gets 3 requests
Server B (weight: 1) -> Gets 1 request
Server C (weight: 2) -> Gets 2 requests
```

### Least Connections
Sends requests to the server with fewest active connections:
```
Server A: 5 connections
Server B: 2 connections  -> Selected
Server C: 8 connections
```

### IP Hash
Uses client IP to determine server assignment:
```
Client IP: 192.168.1.100 -> Hash -> Server B
Client IP: 192.168.1.101 -> Hash -> Server A
```

## Configuration Example
```json
{
  "algorithm": "weighted_round_robin",
  "servers": [
    {
      "id": "web-1",
      "host": "192.168.1.10",
      "port": 8080,
      "weight": 3,
      "enabled": true,
      "health_check": "/health"
    },
    {
      "id": "web-2",
      "host": "192.168.1.11",
      "port": 8080,
      "weight": 2,
      "enabled": true,
      "health_check": "/health"
    },
    {
      "id": "web-3",
      "host": "192.168.1.12",
      "port": 8080,
      "weight": 1,
      "enabled": true,
      "health_check": "/health"
    }
  ],
  "health_check": {
    "interval": 30,
    "timeout": 5,
    "failures": 3
  },
  "session_persistence": {
    "enabled": true,
    "method": "cookie",
    "cookie_name": "SERVERID"
  }
}
```

## API Endpoints
```
# Load Balancer Management
GET    /load-balancer/status
GET    /load-balancer/servers
POST   /load-balancer/servers
PUT    /load-balancer/servers/{id}
DELETE /load-balancer/servers/{id}
GET    /load-balancer/stats
POST   /load-balancer/configure

# Load Balanced Services
GET    /api/users
POST   /api/users
GET    /api/posts
POST   /api/posts
```

## Health Check Configuration
```php
[
    'active' => [
        'enabled' => true,
        'interval' => 30,        // seconds
        'timeout' => 5,          // seconds
        'path' => '/health',
        'expected_status' => 200
    ],
    'passive' => [
        'enabled' => true,
        'failure_threshold' => 3,
        'failure_window' => 60   // seconds
    ]
]
```

## Example Usage
```php
$loadBalancer = new LoadBalancer([
    'algorithm' => 'weighted_round_robin',
    'servers' => [
        ['host' => '192.168.1.10', 'port' => 8080, 'weight' => 3],
        ['host' => '192.168.1.11', 'port' => 8080, 'weight' => 2],
        ['host' => '192.168.1.12', 'port' => 8080, 'weight' => 1]
    ]
]);

$selectedServer = $loadBalancer->selectServer($request);
$response = $loadBalancer->forwardRequest($request, $selectedServer);
```

## Evaluation Criteria
- [ ] Load balancing algorithms work correctly
- [ ] Health checks monitor server status
- [ ] Server failure detection functions
- [ ] Sticky sessions maintain affinity
- [ ] Weighted distribution respects weights
- [ ] Dynamic server registration works
- [ ] Metrics collection is accurate
- [ ] Configuration reloading functions
- [ ] Code is well-organized and documented
- [ ] Tests cover load balancing functionality

## Resources
- [Load Balancing Algorithms](https://www.nginx.com/resources/glossary/load-balancing/)
- [Round Robin Scheduling](https://en.wikipedia.org/wiki/Round-robin_scheduling)
- [Least Connections Algorithm](https://www.f5.com/services/resources/glossary/least-connections)
- [HAProxy Load Balancing](https://www.haproxy.org/)
- [NGINX Load Balancing](https://docs.nginx.com/nginx/admin-guide/load-balancer/)