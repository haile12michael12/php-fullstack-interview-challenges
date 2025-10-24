# Challenge 89: Monitoring and Logging

## Description
In this challenge, you'll implement a comprehensive monitoring and logging system to track application performance, detect issues, and maintain system observability. Monitoring and logging are essential for maintaining system reliability, troubleshooting problems, and optimizing performance.

## Learning Objectives
- Understand monitoring and logging concepts and best practices
- Implement application logging and log management
- Create system monitoring and alerting
- Handle log aggregation and analysis
- Manage metrics collection and visualization
- Implement distributed tracing and debugging

## Requirements
Create a monitoring and logging system with the following features:

1. **Logging Management**:
   - Structured logging
   - Log levels and filtering
   - Log rotation and retention
   - Log aggregation and storage
   - Log search and querying
   - Log shipping and forwarding

2. **System Monitoring**:
   - Metrics collection
   - Health checks and status monitoring
   - Performance monitoring
   - Resource utilization tracking
   - Service dependency monitoring
   - Custom metric definitions

3. **Alerting and Notification**:
   - Alert rules and thresholds
   - Notification channels
   - Alert deduplication and grouping
   - Alert escalation policies
   - Alert silencing and inhibition
   - Incident management

4. **Advanced Features**:
   - Log analysis and pattern detection
   - Anomaly detection
   - Dashboard and visualization
   - Report generation
   - Integration with external tools
   - Compliance and audit logging

## Features to Implement
- [ ] Structured logging
- [ ] Log levels and filtering
- [ ] Log rotation and retention
- [ ] Log aggregation and storage
- [ ] Metrics collection
- [ ] Health checks and status monitoring
- [ ] Performance monitoring
- [ ] Alert rules and thresholds
- [ ] Notification channels
- [ ] Log analysis and pattern detection
- [ ] Dashboard and visualization
- [ ] Report generation

## Project Structure
```
backend-php/
├── src/
│   ├── Monitoring/
│   │   ├── LogManager.php
│   │   ├── MetricsManager.php
│   │   ├── AlertManager.php
│   │   ├── HealthChecker.php
│   │   ├── PerformanceMonitor.php
│   │   └── DashboardManager.php
│   ├── Logging/
│   │   ├── Logger.php
│   │   ├── LogFormatter.php
│   │   ├── LogRotator.php
│   │   ├── LogAggregator.php
│   │   └── LogShipper.php
│   ├── Analysis/
│   │   ├── LogAnalyzer.php
│   │   ├── AnomalyDetector.php
│   │   ├── PatternMatcher.php
│   │   └── TrendAnalyzer.php
│   └── Services/
├── public/
│   └── index.php
├── monitoring/
│   ├── dashboards/
│   │   ├── system-dashboard.json
│   │   ├── application-dashboard.json
│   │   └── database-dashboard.json
│   ├── alerts/
│   │   ├── system-alerts.yaml
│   │   └── application-alerts.yaml
│   ├── configs/
│   │   ├── prometheus.yml
│   │   └── grafana-datasources.yaml
│   └── scripts/
├── storage/
│   └── logs/
├── config/
│   └── monitoring.php
└── vendor/

frontend-react/
├── src/
│   ├── api/
│   │   └── monitoring.js
│   ├── components/
│   │   ├── Dashboard/
│   │   │   ├── MetricsDashboard.jsx
│   │   │   ├── LogViewer.jsx
│   │   │   └── AlertList.jsx
│   │   ├── Logs/
│   │   │   ├── LogSearch.jsx
│   │   │   └── LogAnalysis.jsx
│   │   ├── Metrics/
│   │   │   ├── MetricCharts.jsx
│   │   │   └── PerformanceReport.jsx
│   │   └── Alerts/
│   │       ├── AlertRules.jsx
│   │       └── IncidentList.jsx
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

## Logging Management

### Structured Logger
Implement structured logging with context:
```php
class Logger
{
    private $level;
    private $formatter;
    private $handlers;
    
    public function __construct($level = 'info', $formatter = null)
    {
        $this->level = $level;
        $this->formatter = $formatter ?: new JsonLogFormatter();
        $this->handlers = [];
    }
    
    public function addHandler($handler)
    {
        $this->handlers[] = $handler;
    }
    
    public function debug($message, $context = [])
    {
        $this->log('debug', $message, $context);
    }
    
    public function info($message, $context = [])
    {
        $this->log('info', $message, $context);
    }
    
    public function warning($message, $context = [])
    {
        $this->log('warning', $message, $context);
    }
    
    public function error($message, $context = [])
    {
        $this->log('error', $message, $context);
    }
    
    public function critical($message, $context = [])
    {
        $this->log('critical', $message, $context);
    }
    
    private function log($level, $message, $context = [])
    {
        // Check if log level is enabled
        if (!$this->isLevelEnabled($level)) {
            return;
        }
        
        // Add default context
        $logContext = array_merge([
            'timestamp' => $this->getTimestamp(),
            'level' => $level,
            'message' => $message,
            'service' => config('app.name', 'unknown'),
            'host' => gethostname(),
            'pid' => getmypid()
        ], $context);
        
        // Format the log entry
        $formatted = $this->formatter->format($logContext);
        
        // Send to all handlers
        foreach ($this->handlers as $handler) {
            $handler->handle($formatted, $level);
        }
    }
    
    private function isLevelEnabled($level)
    {
        $levels = ['debug' => 0, 'info' => 1, 'warning' => 2, 'error' => 3, 'critical' => 4];
        return $levels[$level] >= $levels[$this->level];
    }
    
    private function getTimestamp()
    {
        return date('c'); // ISO 8601 format
    }
}

class JsonLogFormatter
{
    public function format($context)
    {
        return json_encode($context);
    }
}

class LogFileHandler
{
    private $filePath;
    private $maxSize;
    
    public function __construct($filePath, $maxSize = 10485760) // 10MB
    {
        $this->filePath = $filePath;
        $this->maxSize = $maxSize;
    }
    
    public function handle($formattedMessage, $level)
    {
        // Check if file needs rotation
        if (file_exists($this->filePath) && filesize($this->filePath) > $this->maxSize) {
            $this->rotate();
        }
        
        // Write to log file
        file_put_contents($this->filePath, $formattedMessage . PHP_EOL, FILE_APPEND | LOCK_EX);
    }
    
    private function rotate()
    {
        $timestamp = date('Y-m-d-H-i-s');
        $rotatedFile = $this->filePath . '.' . $timestamp;
        rename($this->filePath, $rotatedFile);
    }
}
```

### Log Aggregator
Aggregate logs from multiple sources:
```php
class LogAggregator
{
    private $storage;
    private $parsers;
    
    public function __construct($storage)
    {
        $this->storage = $storage;
        $this->parsers = [
            new JsonLogParser(),
            new SyslogParser(),
            new ApacheLogParser()
        ];
    }
    
    public function collectLogs($source, $logs)
    {
        $parsedLogs = [];
        
        foreach ($logs as $logLine) {
            $parsedLog = $this->parseLog($logLine);
            if ($parsedLog) {
                $parsedLog['source'] = $source;
                $parsedLogs[] = $parsedLog;
            }
        }
        
        // Store parsed logs
        $this->storage->storeLogs($parsedLogs);
        
        return count($parsedLogs);
    }
    
    private function parseLog($logLine)
    {
        foreach ($this->parsers as $parser) {
            $parsed = $parser->parse($logLine);
            if ($parsed) {
                return $parsed;
            }
        }
        
        // If no parser matched, return raw log
        return [
            'timestamp' => $this->extractTimestamp($logLine),
            'message' => $logLine,
            'level' => 'info'
        ];
    }
    
    private function extractTimestamp($logLine)
    {
        // Try to extract timestamp from log line
        if (preg_match('/\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}/', $logLine, $matches)) {
            return $matches[0];
        }
        
        return date('c');
    }
}

class JsonLogParser
{
    public function parse($logLine)
    {
        $data = json_decode($logLine, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $data;
        }
        return null;
    }
}
```

## System Monitoring

### Metrics Manager
Collect and manage system metrics:
```php
class MetricsManager
{
    private $collectors;
    private $storage;
    
    public function __construct($storage)
    {
        $this->collectors = [];
        $this->storage = $storage;
    }
    
    public function addCollector($collector)
    {
        $this->collectors[] = $collector;
    }
    
    public function collectMetrics()
    {
        $metrics = [];
        
        foreach ($this->collectors as $collector) {
            $collectorMetrics = $collector->collect();
            $metrics = array_merge($metrics, $collectorMetrics);
        }
        
        // Store metrics
        $this->storage->storeMetrics($metrics);
        
        return $metrics;
    }
    
    public function getMetrics($query = [])
    {
        return $this->storage->queryMetrics($query);
    }
    
    public function getMetric($name, $labels = [])
    {
        return $this->storage->getMetric($name, $labels);
    }
}

class SystemMetricsCollector
{
    public function collect()
    {
        return [
            'system_cpu_usage' => [
                'value' => $this->getCpuUsage(),
                'labels' => ['host' => gethostname()],
                'timestamp' => time()
            ],
            'system_memory_usage' => [
                'value' => $this->getMemoryUsage(),
                'labels' => ['host' => gethostname()],
                'timestamp' => time()
            ],
            'system_disk_usage' => [
                'value' => $this->getDiskUsage(),
                'labels' => ['host' => gethostname(), 'mount' => '/'],
                'timestamp' => time()
            ],
            'system_load_average' => [
                'value' => $this->getLoadAverage(),
                'labels' => ['host' => gethostname()],
                'timestamp' => time()
            ]
        ];
    }
    
    private function getCpuUsage()
    {
        // In a real implementation, you would get actual CPU usage
        // This is a simplified example
        return rand(0, 100);
    }
    
    private function getMemoryUsage()
    {
        $memoryInfo = $this->getMemoryInfo();
        return ($memoryInfo['used'] / $memoryInfo['total']) * 100;
    }
    
    private function getDiskUsage()
    {
        $diskInfo = disk_total_space('/') - disk_free_space('/');
        $total = disk_total_space('/');
        return ($diskInfo / $total) * 100;
    }
    
    private function getLoadAverage()
    {
        $load = sys_getloadavg();
        return $load[0]; // 1-minute load average
    }
    
    private function getMemoryInfo()
    {
        // Simplified memory info
        return [
            'total' => 8 * 1024 * 1024 * 1024, // 8GB
            'used' => rand(2, 6) * 1024 * 1024 * 1024, // 2-6GB
            'free' => rand(2, 6) * 1024 * 1024 * 1024 // 2-6GB
        ];
    }
}

class ApplicationMetricsCollector
{
    public function collect()
    {
        return [
            'app_requests_total' => [
                'value' => $this->getRequestCount(),
                'labels' => ['service' => config('app.name')],
                'timestamp' => time()
            ],
            'app_response_time_seconds' => [
                'value' => $this->getResponseTime(),
                'labels' => ['service' => config('app.name')],
                'timestamp' => time()
            ],
            'app_error_rate' => [
                'value' => $this->getErrorRate(),
                'labels' => ['service' => config('app.name')],
                'timestamp' => time()
            ],
            'app_active_users' => [
                'value' => $this->getActiveUsers(),
                'labels' => ['service' => config('app.name')],
                'timestamp' => time()
            ]
        ];
    }
    
    private function getRequestCount()
    {
        // In a real implementation, you would track actual requests
        static $count = 0;
        return $count += rand(1, 10);
    }
    
    private function getResponseTime()
    {
        // Return response time in seconds
        return rand(50, 500) / 1000; // 50ms to 500ms
    }
    
    private function getErrorRate()
    {
        // Return error rate as percentage
        return rand(0, 500) / 10000; // 0% to 5%
    }
    
    private function getActiveUsers()
    {
        return rand(10, 1000);
    }
}
```

## Alerting System

### Alert Manager
Manage alerts and notifications:
```php
class AlertManager
{
    private $rules;
    private $notifiers;
    private $silences;
    
    public function __construct()
    {
        $this->rules = [];
        $this->notifiers = [];
        $this->silences = [];
    }
    
    public function addRule($rule)
    {
        $this->rules[] = $rule;
    }
    
    public function addNotifier($notifier)
    {
        $this->notifiers[] = $notifier;
    }
    
    public function evaluateAlerts($metrics)
    {
        $activeAlerts = [];
        
        foreach ($this->rules as $rule) {
            if ($rule->evaluate($metrics)) {
                $alert = new Alert([
                    'name' => $rule->getName(),
                    'severity' => $rule->getSeverity(),
                    'summary' => $rule->getSummary(),
                    'description' => $rule->getDescription(),
                    'labels' => $rule->getLabels(),
                    'annotations' => $rule->getAnnotations(),
                    'starts_at' => time(),
                    'ends_at' => null
                ]);
                
                // Check if alert is silenced
                if (!$this->isSilenced($alert)) {
                    $activeAlerts[] = $alert;
                }
            }
        }
        
        // Send notifications for active alerts
        $this->sendNotifications($activeAlerts);
        
        return $activeAlerts;
    }
    
    public function addSilence($matcher, $duration, $comment = '')
    {
        $silence = [
            'id' => uniqid('silence_'),
            'matcher' => $matcher,
            'starts_at' => time(),
            'ends_at' => time() + $duration,
            'comment' => $comment,
            'created_by' => 'system'
        ];
        
        $this->silences[] = $silence;
        return $silence['id'];
    }
    
    private function isSilenced($alert)
    {
        $now = time();
        
        foreach ($this->silences as $silence) {
            if ($silence['starts_at'] <= $now && $silence['ends_at'] >= $now) {
                // Check if alert matches silence criteria
                if ($this->matchesSilence($alert, $silence['matcher'])) {
                    return true;
                }
            }
        }
        
        return false;
    }
    
    private function matchesSilence($alert, $matcher)
    {
        foreach ($matcher as $key => $value) {
            if (!isset($alert->getLabels()[$key]) || $alert->getLabels()[$key] !== $value) {
                return false;
            }
        }
        return true;
    }
    
    private function sendNotifications($alerts)
    {
        foreach ($alerts as $alert) {
            foreach ($this->notifiers as $notifier) {
                $notifier->notify($alert);
            }
        }
    }
}

class AlertRule
{
    private $name;
    private $severity;
    private $summary;
    private $description;
    private $labels;
    private $annotations;
    private $condition;
    
    public function __construct($config)
    {
        $this->name = $config['name'];
        $this->severity = $config['severity'] ?? 'warning';
        $this->summary = $config['summary'];
        $this->description = $config['description'] ?? '';
        $this->labels = $config['labels'] ?? [];
        $this->annotations = $config['annotations'] ?? [];
        $this->condition = $config['condition'];
    }
    
    public function evaluate($metrics)
    {
        // Evaluate the condition against metrics
        // This is a simplified example
        if (is_callable($this->condition)) {
            return call_user_func($this->condition, $metrics);
        }
        
        // For string conditions, parse and evaluate
        return $this->evaluateStringCondition($this->condition, $metrics);
    }
    
    private function evaluateStringCondition($condition, $metrics)
    {
        // Simple condition evaluation
        // Example: "system_cpu_usage > 80"
        if (preg_match('/([a-zA-Z0-9_]+)\s*([<>!=]+)\s*(\d+)/', $condition, $matches)) {
            $metricName = $matches[1];
            $operator = $matches[2];
            $threshold = (float)$matches[3];
            
            if (isset($metrics[$metricName])) {
                $value = $metrics[$metricName]['value'];
                return $this->compare($value, $operator, $threshold);
            }
        }
        
        return false;
    }
    
    private function compare($value, $operator, $threshold)
    {
        switch ($operator) {
            case '>': return $value > $threshold;
            case '<': return $value < $threshold;
            case '>=': return $value >= $threshold;
            case '<=': return $value <= $threshold;
            case '==': return $value == $threshold;
            case '!=': return $value != $threshold;
            default: return false;
        }
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getSeverity()
    {
        return $this->severity;
    }
    
    public function getSummary()
    {
        return $this->summary;
    }
    
    public function getDescription()
    {
        return $this->description;
    }
    
    public function getLabels()
    {
        return $this->labels;
    }
    
    public function getAnnotations()
    {
        return $this->annotations;
    }
}

class Alert
{
    private $data;
    
    public function __construct($data)
    {
        $this->data = $data;
    }
    
    public function getName()
    {
        return $this->data['name'];
    }
    
    public function getSeverity()
    {
        return $this->data['severity'];
    }
    
    public function getLabels()
    {
        return $this->data['labels'];
    }
    
    public function getAnnotations()
    {
        return $this->data['annotations'];
    }
    
    public function getStartsAt()
    {
        return $this->data['starts_at'];
    }
    
    public function isActive()
    {
        return $this->data['ends_at'] === null;
    }
}
```

## Configuration Example
```php
[
    'logging' => [
        'level' => 'info',
        'format' => 'json',
        'handlers' => [
            'file' => [
                'enabled' => true,
                'path' => '/var/log/application.log',
                'max_size' => '100MB',
                'rotation' => 'daily'
            ],
            'syslog' => [
                'enabled' => true,
                'host' => 'localhost',
                'port' => 514
            ],
            'elasticsearch' => [
                'enabled' => false,
                'hosts' => ['http://localhost:9200'],
                'index' => 'application-logs'
            ]
        ],
        'structured' => true,
        'context' => [
            'service' => env('APP_NAME', 'application'),
            'environment' => env('APP_ENV', 'production'),
            'version' => env('APP_VERSION', '1.0.0')
        ]
    ],
    'metrics' => [
        'collection_interval' => 30, // seconds
        'storage' => 'prometheus',
        'prometheus' => [
            'host' => 'localhost',
            'port' => 9090,
            'retention' => '15d'
        ],
        'collectors' => [
            'system' => [
                'enabled' => true,
                'metrics' => [
                    'cpu_usage' => true,
                    'memory_usage' => true,
                    'disk_usage' => true,
                    'load_average' => true,
                    'network_io' => true
                ]
            ],
            'application' => [
                'enabled' => true,
                'metrics' => [
                    'request_count' => true,
                    'response_time' => true,
                    'error_rate' => true,
                    'active_users' => true,
                    'database_queries' => true
                ]
            ]
        ]
    ],
    'alerts' => [
        'enabled' => true,
        'evaluation_interval' => 60, // seconds
        'rules' => [
            'high_cpu_usage' => [
                'name' => 'High CPU Usage',
                'severity' => 'warning',
                'summary' => 'CPU usage is high',
                'description' => 'CPU usage has exceeded 80% for more than 5 minutes',
                'labels' => [
                    'alertname' => 'HighCPUUsage',
                    'severity' => 'warning'
                ],
                'condition' => 'system_cpu_usage > 80'
            ],
            'high_memory_usage' => [
                'name' => 'High Memory Usage',
                'severity' => 'warning',
                'summary' => 'Memory usage is high',
                'description' => 'Memory usage has exceeded 85% for more than 5 minutes',
                'labels' => [
                    'alertname' => 'HighMemoryUsage',
                    'severity' => 'warning'
                ],
                'condition' => 'system_memory_usage > 85'
            ],
            'high_error_rate' => [
                'name' => 'High Error Rate',
                'severity' => 'critical',
                'summary' => 'Application error rate is high',
                'description' => 'Error rate has exceeded 5% for more than 2 minutes',
                'labels' => [
                    'alertname' => 'HighErrorRate',
                    'severity' => 'critical'
                ],
                'condition' => 'app_error_rate > 0.05'
            ]
        ]
    ],
    'notifications' => [
        'channels' => [
            'slack' => [
                'enabled' => true,
                'webhook_url' => env('SLACK_WEBHOOK_URL'),
                'channel' => '#alerts'
            ],
            'email' => [
                'enabled' => true,
                'smtp' => [
                    'host' => env('SMTP_HOST'),
                    'port' => env('SMTP_PORT', 587),
                    'username' => env('SMTP_USERNAME'),
                    'password' => env('SMTP_PASSWORD')
                ],
                'recipients' => ['ops@example.com']
            ],
            'pagerduty' => [
                'enabled' => false,
                'integration_key' => env('PAGERDUTY_INTEGRATION_KEY')
            ]
        ]
    ],
    'analysis' => [
        'log_analysis' => [
            'enabled' => true,
            'pattern_detection' => true,
            'anomaly_detection' => true,
            'trend_analysis' => true
        ],
        'retention' => [
            'logs' => '30d',
            'metrics' => '90d',
            'alerts' => '1y'
        ]
    ]
]
```

## API Endpoints
```
# Monitoring and Logging Management
GET    /monitoring/metrics              # Get current metrics
GET    /monitoring/metrics/{name}       # Get specific metric
POST   /monitoring/metrics/query        # Query metrics
GET    /monitoring/logs                 # Get logs
POST   /monitoring/logs/search          # Search logs
GET    /monitoring/logs/{id}            # Get specific log entry
GET    /monitoring/alerts               # Get active alerts
POST   /monitoring/alerts/silence       # Silence alerts
DELETE /monitoring/alerts/silence/{id}  # Remove silence
GET    /monitoring/rules                # List alert rules
POST   /monitoring/rules                # Create alert rule
GET    /monitoring/rules/{name}         # Get alert rule
PUT    /monitoring/rules/{name}         # Update alert rule
DELETE /monitoring/rules/{name}         # Delete alert rule
GET    /monitoring/health               # Get system health
GET    /monitoring/dashboard            # Get dashboard data
GET    /monitoring/reports              # Get monitoring reports
POST   /monitoring/reports/generate     # Generate custom report
```

## Metrics Response
```json
{
  "metrics": [
    {
      "name": "system_cpu_usage",
      "value": 75.5,
      "labels": {
        "host": "web-server-1"
      },
      "timestamp": "2023-01-01T10:00:00Z"
    },
    {
      "name": "system_memory_usage",
      "value": 65.2,
      "labels": {
        "host": "web-server-1"
      },
      "timestamp": "2023-01-01T10:00:00Z"
    },
    {
      "name": "app_requests_total",
      "value": 12540,
      "labels": {
        "service": "web-app",
        "endpoint": "/api/users"
      },
      "timestamp": "2023-01-01T10:00:00Z"
    }
  ],
  "timestamp": "2023-01-01T10:00:00Z"
}
```

## Alert Response
```json
{
  "alerts": [
    {
      "id": "alert_5f4d4a4b4c4d4e4f50515253",
      "name": "High CPU Usage",
      "severity": "warning",
      "summary": "CPU usage is high",
      "description": "CPU usage has exceeded 80% for more than 5 minutes",
      "labels": {
        "alertname": "HighCPUUsage",
        "severity": "warning",
        "host": "web-server-1"
      },
      "annotations": {
        "summary": "CPU usage is at 85%",
        "description": "CPU usage on web-server-1 is at 85%"
      },
      "starts_at": "2023-01-01T09:55:00Z",
      "ends_at": null,
      "status": "active"
    }
  ]
}
```

## Log Entry Response
```json
{
  "logs": [
    {
      "id": "log_5f4d4a4b4c4d4e4f50515253",
      "timestamp": "2023-01-01T10:00:00Z",
      "level": "error",
      "message": "Database connection failed",
      "service": "web-app",
      "host": "web-server-1",
      "context": {
        "exception": "PDOException",
        "file": "/app/src/Database/Connection.php",
        "line": 42,
        "trace": "..."
      }
    }
  ]
}
```

## Evaluation Criteria
- [ ] Structured logging implemented correctly
- [ ] Log levels and filtering work
- [ ] Log rotation and retention function
- [ ] Log aggregation and storage successful
- [ ] Metrics collection operates correctly
- [ ] Health checks and status monitoring work
- [ ] Performance monitoring functions
- [ ] Alert rules and thresholds implemented
- [ ] Notification channels work
- [ ] Log analysis and pattern detection operate
- [ ] Dashboard and visualization function
- [ ] Report generation works
- [ ] Code is well-organized and documented
- [ ] Tests cover monitoring and logging functionality

## Resources
- [Monitoring and Observability](https://www.oreilly.com/library/view/distributed-systems-observability/9781492033431/)
- [Prometheus](https://prometheus.io/)
- [Grafana](https://grafana.com/)
- [ELK Stack](https://www.elastic.co/elk-stack)
- [OpenTelemetry](https://opentelemetry.io/)