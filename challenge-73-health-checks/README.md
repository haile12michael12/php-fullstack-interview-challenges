# Challenge 73: Health Checks

## Description
In this challenge, you'll implement comprehensive health checking mechanisms to monitor the status of your applications and infrastructure. Health checks are critical for ensuring system reliability and enabling automated failover.

## Learning Objectives
- Understand different types of health checks
- Implement active and passive health monitoring
- Create custom health check indicators
- Handle health check aggregation
- Implement health check endpoints
- Manage health check thresholds and timeouts

## Requirements
Create a health checking system with the following features:

1. **Health Check Types**:
   - Liveness checks (is the application running?)
   - Readiness checks (is the application ready to serve requests?)
   - Startup checks (is the application starting correctly?)
   - Custom business logic checks
   - Dependency health checks (database, cache, external services)

2. **Monitoring Mechanisms**:
   - Active health probing
   - Passive health monitoring
   - Health check scheduling
   - Threshold-based status changes
   - Grace periods for recovery
   - Retry mechanisms

3. **Status Management**:
   - Health status aggregation
   - Individual component status tracking
   - Status change notifications
   - Historical status tracking
   - Degraded state handling
   - Automatic recovery detection

4. **Reporting and Integration**:
   - Health check endpoints
   - JSON/XML response formats
   - Metrics integration
   - Alerting system integration
   - Dashboard reporting
   - Log aggregation

## Features to Implement
- [ ] Liveness health checks
- [ ] Readiness health checks
- [ ] Startup health checks
- [ ] Custom business logic checks
- [ ] Dependency health checks
- [ ] Active health probing
- [ ] Passive health monitoring
- [ ] Health status aggregation
- [ ] Status change notifications
- [ ] Health check endpoints
- [ ] Metrics integration
- [ ] Alerting integration

## Project Structure
```
backend-php/
├── src/
│   ├── Health/
│   │   ├── HealthChecker.php
│   │   ├── HealthIndicator.php
│   │   ├── Status/
│   │   │   ├── HealthStatus.php
│   │   │   ├── StatusAggregator.php
│   │   │   └── StatusChangeNotifier.php
│   │   ├── Indicators/
│   │   │   ├── DatabaseHealthIndicator.php
│   │   │   ├── CacheHealthIndicator.php
│   │   │   ├── DiskSpaceHealthIndicator.php
│   │   │   ├── MemoryHealthIndicator.php
│   │   │   └── CustomBusinessHealthIndicator.php
│   │   ├── Checks/
│   │   │   ├── ActiveCheck.php
│   │   │   ├── PassiveCheck.php
│   │   │   └── ScheduledCheck.php
│   │   └── Events/
│   │       ├── HealthStatusChanged.php
│   │       └── HealthCheckFailed.php
│   ├── Http/
│   │   ├── Request.php
│   │   ├── Response.php
│   │   └── HttpClient.php
│   └── Services/
├── public/
│   └── index.php
├── storage/
│   └── health-checks.json
├── config/
│   └── health.php
└── vendor/

frontend-react/
├── src/
│   ├── api/
│   │   └── health.js
│   ├── components/
│   │   └── HealthDashboard.jsx
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

## Health Check Types

### Liveness Check
Determines if the application is running:
```php
class LivenessHealthIndicator implements HealthIndicator
{
    public function check(): HealthStatus
    {
        // Simple check to see if the process is alive
        return HealthStatus::up()->withDetail('process', 'running');
    }
}
```

### Readiness Check
Determines if the application is ready to serve requests:
```php
class ReadinessHealthIndicator implements HealthIndicator
{
    public function check(): HealthStatus
    {
        // Check if all dependencies are available
        if ($this->database->isConnected() && 
            $this->cache->isAvailable() && 
            $this->queue->isProcessing()) {
            return HealthStatus::up()
                ->withDetail('database', 'connected')
                ->withDetail('cache', 'available')
                ->withDetail('queue', 'processing');
        }
        
        return HealthStatus::down()
            ->withDetail('reason', 'dependencies not ready');
    }
}
```

### Custom Business Logic Check
Validates business-critical functionality:
```php
class PaymentServiceHealthIndicator implements HealthIndicator
{
    public function check(): HealthStatus
    {
        try {
            // Test a small payment transaction
            $result = $this->paymentService->testTransaction();
            if ($result->isSuccessful()) {
                return HealthStatus::up()
                    ->withDetail('payment_service', 'operational');
            }
        } catch (Exception $e) {
            return HealthStatus::down()
                ->withDetail('payment_service', 'unavailable')
                ->withDetail('error', $e->getMessage());
        }
    }
}
```

## Configuration Example
```php
[
    'indicators' => [
        'database' => [
            'class' => DatabaseHealthIndicator::class,
            'enabled' => true,
            'critical' => true
        ],
        'cache' => [
            'class' => CacheHealthIndicator::class,
            'enabled' => true,
            'critical' => false
        ],
        'payment_service' => [
            'class' => PaymentServiceHealthIndicator::class,
            'enabled' => true,
            'critical' => true,
            'timeout' => 5
        ]
    ],
    'checks' => [
        'active' => [
            'interval' => 30,
            'timeout' => 5,
            'retries' => 3
        ],
        'passive' => [
            'enabled' => true,
            'threshold' => 5
        ]
    ],
    'thresholds' => [
        'down_after_failures' => 3,
        'up_after_successes' => 2,
        'grace_period' => 60
    ]
]
```

## API Endpoints
```
# Health Check Endpoints
GET /health                 # Overall health status
GET /health/liveness        # Liveness check only
GET /health/readiness       # Readiness check only
GET /health/startup         # Startup check only
GET /health/components      # All component statuses
GET /health/components/{name} # Specific component status
GET /health/history         # Health status history
```

## Health Check Response Format
```json
{
  "status": "UP",
  "components": {
    "database": {
      "status": "UP",
      "details": {
        "database": "MySQL 8.0",
        "connection": "established"
      }
    },
    "cache": {
      "status": "UP",
      "details": {
        "provider": "Redis",
        "version": "6.2.6"
      }
    },
    "payment_service": {
      "status": "UP",
      "details": {
        "response_time_ms": 45,
        "last_check": "2023-01-01T10:00:00Z"
      }
    }
  },
  "timestamp": "2023-01-01T10:00:00Z"
}
```

## Degraded Status Response
```json
{
  "status": "DEGRADED",
  "components": {
    "database": {
      "status": "UP",
      "details": {
        "database": "MySQL 8.0",
        "connection": "established"
      }
    },
    "cache": {
      "status": "DOWN",
      "details": {
        "error": "Connection timeout",
        "last_error": "2023-01-01T09:59:30Z"
      }
    },
    "payment_service": {
      "status": "UP",
      "details": {
        "response_time_ms": 120,
        "warning": "Response time degraded"
      }
    }
  },
  "timestamp": "2023-01-01T10:00:00Z"
}
```

## Evaluation Criteria
- [ ] Liveness checks determine application running state
- [ ] Readiness checks validate service availability
- [ ] Startup checks verify initialization
- [ ] Custom business logic checks function
- [ ] Dependency health checks monitor external services
- [ ] Active health probing works
- [ ] Passive health monitoring detects issues
- [ ] Health status aggregation is accurate
- [ ] Status change notifications trigger
- [ ] Health check endpoints return correct data
- [ ] Metrics integration collects data
- [ ] Alerting integration functions
- [ ] Code is well-organized and documented
- [ ] Tests cover health check functionality

## Resources
- [Health Check Patterns](https://microservices.io/patterns/observability/health-check-api.html)
- [Kubernetes Liveness and Readiness Probes](https://kubernetes.io/docs/tasks/configure-pod-container/configure-liveness-readiness-startup-probes/)
- [Spring Boot Health Indicators](https://docs.spring.io/spring-boot/docs/current/reference/html/actuator.html#actuator.endpoints.health)
- [Health Check Best Practices](https://cloud.google.com/blog/products/management-tools/kubernetes-best-practices-setting-up-health-checks-with-readiness-and-liveness-probes)