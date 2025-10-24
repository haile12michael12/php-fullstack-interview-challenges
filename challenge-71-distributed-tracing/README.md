# Challenge 71: Distributed Tracing

## Description
In this challenge, you'll implement distributed tracing to monitor and debug requests as they flow through multiple services in a microservices architecture. Distributed tracing helps identify performance bottlenecks and troubleshoot issues across service boundaries.

## Learning Objectives
- Understand distributed tracing concepts and benefits
- Implement trace and span creation
- Propagate trace context between services
- Collect and export tracing data
- Analyze trace data for performance insights
- Integrate with tracing backends

## Requirements
Create a distributed tracing system with the following features:

1. **Trace Context Management**:
   - Trace ID generation and propagation
   - Span ID generation and hierarchy
   - Parent-child span relationships
   - Trace context injection and extraction
   - HTTP header propagation (W3C Trace Context)
   - Context propagation across async operations

2. **Span Lifecycle**:
   - Span creation and completion
   - Timing and duration tracking
   - Attribute and annotation support
   - Error and exception recording
   - Span sampling strategies
   - Resource and service metadata

3. **Data Collection and Export**:
   - In-memory trace storage
   - Batch processing of trace data
   - Export to tracing backends (Jaeger, Zipkin)
   - Data serialization formats
   - Network transport mechanisms
   - Retry and error handling

4. **Integration Features**:
   - HTTP client/server instrumentation
   - Database query tracing
   - Messaging system tracing
   - Custom span creation
   - Automatic instrumentation
   - Manual instrumentation

## Features to Implement
- [ ] Trace and span creation
- [ ] Trace context propagation
- [ ] Span hierarchy management
- [ ] Timing and duration tracking
- [ ] Attribute and annotation support
- [ ] Error recording
- [ ] Sampling strategies
- [ ] Export to tracing backends
- [ ] HTTP client/server instrumentation
- [ ] Database query tracing
- [ ] Custom span creation

## Project Structure
```
backend-php/
├── src/
│   ├── Tracing/
│   │   ├── Tracer.php
│   │   ├── Span.php
│   │   ├── TraceContext.php
│   │   ├── Propagation/
│   │   │   ├── HttpPropagator.php
│   │   │   ├── W3CPropagator.php
│   │   │   └── B3Propagator.php
│   │   ├── Exporters/
│   │   │   ├── JaegerExporter.php
│   │   │   ├── ZipkinExporter.php
│   │   │   └── ConsoleExporter.php
│   │   ├── Samplers/
│   │   │   ├── AlwaysOnSampler.php
│   │   │   ├── AlwaysOffSampler.php
│   │   │   └── ProbabilitySampler.php
│   │   └── Instrumentation/
│   │       ├── HttpInstrumentation.php
│   │       ├── DatabaseInstrumentation.php
│   │       └── MessagingInstrumentation.php
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
│   └── traces/
├── config/
│   └── tracing.php
└── vendor/

frontend-react/
├── src/
│   ├── api/
│   │   └── tracing.js
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

## Trace Context Example
```
Trace ID: 4bf92f3577b34da6a3ce929d0e0e4736
Span ID: 00f067aa0ba902b7
Parent Span ID: 00f067aa0ba902b6
Sampled: 1
```

## HTTP Header Propagation
```http
GET /api/users/123 HTTP/1.1
Host: example.com
traceparent: 00-4bf92f3577b34da6a3ce929d0e0e4736-00f067aa0ba902b7-01
tracestate: rojo=00f067aa0ba902b7,congo=t61rcWkgMzE
```

## API Endpoints
```
# Tracing Management
GET    /tracing/spans
GET    /tracing/traces/{traceId}
GET    /tracing/services
GET    /tracing/dependencies

# Instrumented Services
GET    /api/users/{id}
POST   /api/users
GET    /api/posts/{id}
POST   /api/posts
GET    /api/notifications
POST   /api/notifications
```

## Example Trace Structure
```json
{
  "traceId": "4bf92f3577b34da6a3ce929d0e0e4736",
  "spans": [
    {
      "spanId": "00f067aa0ba902b7",
      "parentSpanId": null,
      "name": "GET /api/users/123",
      "startTime": "2023-01-01T10:00:00.000Z",
      "endTime": "2023-01-01T10:00:00.100Z",
      "duration": 100,
      "attributes": {
        "http.method": "GET",
        "http.url": "/api/users/123",
        "http.status_code": 200
      },
      "events": [
        {
          "name": "DB Query Start",
          "timestamp": "2023-01-01T10:00:00.010Z",
          "attributes": {
            "db.statement": "SELECT * FROM users WHERE id = ?"
          }
        }
      ]
    }
  ]
}
```

## Tracing Configuration
```php
[
    'sampler' => [
        'type' => 'probability',
        'rate' => 0.1  // 10% sampling rate
    ],
    'exporter' => [
        'type' => 'jaeger',
        'endpoint' => 'http://localhost:14268/api/traces'
    ],
    'propagation' => [
        'format' => 'w3c'  // or 'b3'
    ]
]
```

## Evaluation Criteria
- [ ] Trace and span creation works correctly
- [ ] Trace context propagates between services
- [ ] Span hierarchy is maintained
- [ ] Timing and duration tracking is accurate
- [ ] Attributes and annotations are recorded
- [ ] Error recording functions properly
- [ ] Sampling strategies are implemented
- [ ] Export to tracing backends works
- [ ] HTTP client/server instrumentation traces requests
- [ ] Database query tracing captures queries
- [ ] Custom span creation is supported
- [ ] Code is well-organized and documented
- [ ] Tests cover tracing functionality

## Resources
- [Distributed Tracing Concepts](https://opentelemetry.io/docs/concepts/signals/traces/)
- [W3C Trace Context](https://www.w3.org/TR/trace-context/)
- [Jaeger Tracing](https://www.jaegertracing.io/)
- [Zipkin Tracing](https://zipkin.io/)
- [OpenTelemetry PHP](https://github.com/open-telemetry/opentelemetry-php)