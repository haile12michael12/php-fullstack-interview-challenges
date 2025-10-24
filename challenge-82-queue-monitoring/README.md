# Challenge 82: Queue Monitoring

## Description
In this challenge, you'll implement a comprehensive queue monitoring system that provides real-time visibility into job queue performance, health, and operational metrics. Queue monitoring is essential for maintaining system reliability and identifying bottlenecks in background processing.

## Learning Objectives
- Understand queue monitoring concepts and metrics
- Implement real-time queue metrics collection
- Create queue health dashboards
- Handle alerting and notifications
- Manage queue performance optimization
- Implement historical data analysis

## Requirements
Create a queue monitoring system with the following features:

1. **Real-time Metrics**:
   - Queue depth and length tracking
   - Processing rate monitoring
   - Worker utilization metrics
   - Job completion statistics
   - Failure rate tracking
   - Latency measurements

2. **Health Monitoring**:
   - Worker status and availability
   - Queue processing health
   - Resource utilization tracking
   - Error pattern detection
   - System load monitoring
   - Recovery time tracking

3. **Alerting System**:
   - Threshold-based alerts
   - Anomaly detection
   - Escalation policies
   - Notification channels
   - Alert suppression
   - Incident management

4. **Advanced Features**:
   - Historical trend analysis
   - Performance benchmarking
   - Predictive analytics
   - Custom dashboard creation
   - Export and reporting
   - Integration with monitoring tools

## Features to Implement
- [ ] Real-time queue metrics collection
- [ ] Queue depth and length tracking
- [ ] Processing rate monitoring
- [ ] Worker utilization metrics
- [ ] Job completion statistics
- [ ] Failure rate tracking
- [ ] Latency measurements
- [ ] Worker status monitoring
- [ ] Resource utilization tracking
- [ ] Threshold-based alerts
- [ ] Anomaly detection
- [ ] Historical trend analysis
- [ ] Custom dashboard creation

## Project Structure
```
backend-php/
├── src/
│   ├── Monitoring/
│   │   ├── QueueMonitor.php
│   │   ├── Metrics/
│   │   │   ├── MetricsCollector.php
│   │   │   ├── QueueMetrics.php
│   │   │   ├── WorkerMetrics.php
│   │   │   ├── PerformanceMetrics.php
│   │   │   └── HealthMetrics.php
│   │   ├── Dashboards/
│   │   │   ├── DashboardManager.php
│   │   │   ├── QueueDashboard.php
│   │   │   ├── WorkerDashboard.php
│   │   │   └── PerformanceDashboard.php
│   │   ├── Alerts/
│   │   │   ├── AlertManager.php
│   │   │   ├── ThresholdAlert.php
│   │   │   ├── AnomalyAlert.php
│   │   │   └── NotificationManager.php
│   │   ├── Analysis/
│   │   │   ├── TrendAnalyzer.php
│   │   │   ├── PerformanceAnalyzer.php
│   │   │   └── PredictiveAnalyzer.php
│   │   └── Storage/
│   │       ├── MetricsStorage.php
│   │       ├── HistoryManager.php
│   │       └── ReportGenerator.php
│   ├── Queue/
│   │   ├── JobQueue.php
│   │   ├── Worker.php
│   │   └── Job.php
│   ├── Events/
│   │   ├── JobQueued.php
│   │   ├── JobProcessing.php
│   │   ├── JobProcessed.php
│   │   ├── JobFailed.php
│   │   └── WorkerEvent.php
│   └── Services/
├── public/
│   └── index.php
├── storage/
│   └── monitoring/
├── config/
│   └── monitoring.php
└── vendor/

frontend-react/
├── src/
│   ├── api/
│   │   └── monitoring.js
│   ├── components/
│   │   ├── Dashboard/
│   │   │   ├── QueueMetrics.jsx
│   │   │   ├── WorkerMetrics.jsx
│   │   │   └── PerformanceCharts.jsx
│   │   ├── Alerts/
│   │   │   ├── AlertList.jsx
│   │   │   └── AlertSettings.jsx
│   │   └── Reports/
│   │       ├── TrendReport.jsx
│   │       └── PerformanceReport.jsx
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

## Metrics Collection

### Queue Metrics Collector
Collect real-time queue metrics:
```php
class MetricsCollector
{
    private $storage;
    private $sampleInterval;
    
    public function __construct(MetricsStorage $storage, $sampleInterval = 5)
    {
        $this->storage = $storage;
        $this->sampleInterval = $sampleInterval;
    }
    
    public function collectQueueMetrics($queueName)
    {
        $metrics = [
            'timestamp' => time(),
            'queue_name' => $queueName,
            'depth' => $this->getQueueDepth($queueName),
            'processing_rate' => $this->getProcessingRate($queueName),
            'failure_rate' => $this->getFailureRate($queueName),
            'average_latency' => $this->getAverageLatency($queueName),
            'workers_active' => $this->getActiveWorkers($queueName),
            'workers_idle' => $this->getIdleWorkers($queueName)
        ];
        
        $this->storage->store($metrics);
        return $metrics;
    }
    
    public function collectWorkerMetrics($workerId)
    {
        $worker = $this->getWorker($workerId);
        
        $metrics = [
            'timestamp' => time(),
            'worker_id' => $workerId,
            'status' => $worker->getStatus(),
            'jobs_processed' => $worker->getJobsProcessed(),
            'jobs_failed' => $worker->getJobsFailed(),
            'memory_usage' => $worker->getMemoryUsage(),
            'cpu_usage' => $worker->getCpuUsage(),
            'uptime' => $worker->getUptime()
        ];
        
        $this->storage->storeWorkerMetrics($metrics);
        return $metrics;
    }
    
    private function getQueueDepth($queueName)
    {
        return QueueManager::getQueue($queueName)->getPendingCount();
    }
    
    private function getProcessingRate($queueName)
    {
        // Calculate jobs per second over the last minute
        $recentJobs = $this->storage->getRecentJobs($queueName, 60);
        return count($recentJobs) / 60;
    }
    
    private function getFailureRate($queueName)
    {
        $totalJobs = $this->storage->getTotalJobs($queueName);
        $failedJobs = $this->storage->getFailedJobs($queueName);
        return $totalJobs > 0 ? $failedJobs / $totalJobs : 0;
    }
    
    private function getAverageLatency($queueName)
    {
        $recentJobs = $this->storage->getRecentJobs($queueName, 300); // Last 5 minutes
        if (empty($recentJobs)) {
            return 0;
        }
        
        $totalLatency = array_sum(array_map(function($job) {
            return $job->getProcessingTime();
        }, $recentJobs));
        
        return $totalLatency / count($recentJobs);
    }
}
```

### Real-time Data Collection
Continuous metrics collection:
```php
class QueueMonitor
{
    private $collector;
    private $alertManager;
    private $isRunning = false;
    
    public function startMonitoring()
    {
        $this->isRunning = true;
        
        while ($this->isRunning) {
            $this->collectAllMetrics();
            $this->checkAlerts();
            sleep(config('monitoring.collection_interval', 5));
        }
    }
    
    private function collectAllMetrics()
    {
        $queues = QueueManager::getAllQueues();
        
        foreach ($queues as $queueName => $queue) {
            // Collect queue metrics
            $queueMetrics = $this->collector->collectQueueMetrics($queueName);
            
            // Collect worker metrics for this queue
            $workers = WorkerManager::getWorkersForQueue($queueName);
            foreach ($workers as $worker) {
                $this->collector->collectWorkerMetrics($worker->getId());
            }
            
            // Emit events for real-time updates
            event(new QueueMetricsUpdated($queueName, $queueMetrics));
        }
    }
    
    private function checkAlerts()
    {
        $metrics = $this->collector->getLatestMetrics();
        
        foreach ($metrics as $queueName => $queueMetrics) {
            $this->alertManager->checkThresholds($queueName, $queueMetrics);
            $this->alertManager->checkAnomalies($queueName, $queueMetrics);
        }
    }
    
    public function stopMonitoring()
    {
        $this->isRunning = false;
    }
}
```

## Alerting System
```php
class AlertManager
{
    private $thresholds;
    private $notificationChannels;
    private $suppressionRules;
    
    public function checkThresholds($queueName, $metrics)
    {
        $alerts = [];
        
        // Check queue depth threshold
        if (isset($this->thresholds['queue_depth'])) {
            $threshold = $this->thresholds['queue_depth'];
            if ($metrics['depth'] > $threshold['value']) {
                $alerts[] = new ThresholdAlert(
                    'queue_depth',
                    $queueName,
                    $metrics['depth'],
                    $threshold['value'],
                    $threshold['severity']
                );
            }
        }
        
        // Check failure rate threshold
        if (isset($this->thresholds['failure_rate'])) {
            $threshold = $this->thresholds['failure_rate'];
            if ($metrics['failure_rate'] > $threshold['value']) {
                $alerts[] = new ThresholdAlert(
                    'failure_rate',
                    $queueName,
                    $metrics['failure_rate'],
                    $threshold['value'],
                    $threshold['severity']
                );
            }
        }
        
        // Check processing rate threshold
        if (isset($this->thresholds['processing_rate'])) {
            $threshold = $this->thresholds['processing_rate'];
            if ($metrics['processing_rate'] < $threshold['value']) {
                $alerts[] = new ThresholdAlert(
                    'processing_rate',
                    $queueName,
                    $metrics['processing_rate'],
                    $threshold['value'],
                    $threshold['severity']
                );
            }
        }
        
        foreach ($alerts as $alert) {
            $this->triggerAlert($alert);
        }
    }
    
    public function checkAnomalies($queueName, $metrics)
    {
        // Detect unusual patterns in metrics
        $historicalData = $this->getHistoricalData($queueName);
        
        if ($this->isAnomalous($metrics, $historicalData)) {
            $alert = new AnomalyAlert(
                'unusual_pattern',
                $queueName,
                $metrics,
                'Detected unusual pattern in queue metrics'
            );
            $this->triggerAlert($alert);
        }
    }
    
    private function triggerAlert($alert)
    {
        // Check suppression rules
        if ($this->shouldSuppress($alert)) {
            return;
        }
        
        // Store alert
        $this->storeAlert($alert);
        
        // Send notifications
        foreach ($this->notificationChannels as $channel) {
            $channel->send($alert);
        }
        
        // Log the alert
        $this->logAlert($alert);
    }
}
```

## Configuration Example
```php
[
    'collection' => [
        'interval' => 5, // seconds
        'retention' => 86400, // 24 hours
        'storage' => 'database',
        'batch_size' => 100
    ],
    'metrics' => [
        'queue_depth' => true,
        'processing_rate' => true,
        'failure_rate' => true,
        'latency' => true,
        'worker_utilization' => true,
        'memory_usage' => true,
        'cpu_usage' => true
    ],
    'thresholds' => [
        'queue_depth' => [
            'value' => 1000,
            'severity' => 'warning',
            'description' => 'Queue depth exceeds 1000 jobs'
        ],
        'failure_rate' => [
            'value' => 0.05, // 5%
            'severity' => 'critical',
            'description' => 'Job failure rate exceeds 5%'
        ],
        'processing_rate' => [
            'value' => 1.0, // 1 job per second
            'severity' => 'warning',
            'description' => 'Processing rate below 1 job/second'
        ],
        'latency' => [
            'value' => 30, // 30 seconds
            'severity' => 'critical',
            'description' => 'Average job latency exceeds 30 seconds'
        ],
        'worker_utilization' => [
            'value' => 0.95, // 95%
            'severity' => 'warning',
            'description' => 'Worker utilization exceeds 95%'
        ]
    ],
    'alerts' => [
        'channels' => [
            'email' => [
                'enabled' => true,
                'recipients' => ['admin@example.com', 'ops@example.com']
            ],
            'slack' => [
                'enabled' => true,
                'webhook_url' => 'https://hooks.slack.com/services/XXX/YYY/ZZZ'
            ],
            'webhook' => [
                'enabled' => false,
                'url' => 'https://monitoring.example.com/webhook'
            ]
        ],
        'suppression' => [
            'duplicate_window' => 300, // 5 minutes
            'escalation_delay' => 3600 // 1 hour
        ]
    ],
    'dashboards' => [
        'default_refresh' => 10, // seconds
        'history_range' => 3600, // 1 hour
        'custom_dashboards' => [
            'executive' => [
                'metrics' => ['queue_depth', 'processing_rate', 'failure_rate'],
                'refresh' => 30
            ],
            'operations' => [
                'metrics' => ['all'],
                'refresh' => 5
            ]
        ]
    ]
]
```

## Dashboard Components
```php
class DashboardManager
{
    private $dashboards = [];
    
    public function createDashboard($name, $config)
    {
        $dashboard = new Dashboard($name, $config);
        $this->dashboards[$name] = $dashboard;
        return $dashboard;
    }
    
    public function getDashboard($name)
    {
        return $this->dashboards[$name] ?? null;
    }
    
    public function getAllDashboards()
    {
        return $this->dashboards;
    }
}

class QueueDashboard
{
    private $metricsCollector;
    
    public function getQueueOverview()
    {
        $queues = QueueManager::getAllQueues();
        $overview = [];
        
        foreach ($queues as $name => $queue) {
            $overview[$name] = [
                'name' => $name,
                'depth' => $queue->getPendingCount(),
                'processing' => $queue->getProcessingCount(),
                'processed' => $queue->getProcessedCount(),
                'failed' => $queue->getFailedCount(),
                'workers' => WorkerManager::getWorkerCount($name),
                'health' => $this->calculateQueueHealth($name)
            ];
        }
        
        return $overview;
    }
    
    public function getPerformanceMetrics($timeRange = 3600)
    {
        $startTime = time() - $timeRange;
        
        return [
            'processing_rate' => $this->getProcessingRateHistory($startTime, time()),
            'failure_rate' => $this->getFailureRateHistory($startTime, time()),
            'latency' => $this->getLatencyHistory($startTime, time()),
            'queue_depth' => $this->getQueueDepthHistory($startTime, time())
        ];
    }
    
    private function calculateQueueHealth($queueName)
    {
        $metrics = $this->metricsCollector->getLatestMetrics($queueName);
        
        // Simple health calculation based on key metrics
        $score = 100;
        
        if ($metrics['depth'] > 1000) {
            $score -= 20;
        }
        
        if ($metrics['failure_rate'] > 0.05) {
            $score -= 30;
        }
        
        if ($metrics['processing_rate'] < 1.0) {
            $score -= 25;
        }
        
        if ($metrics['average_latency'] > 30) {
            $score -= 25;
        }
        
        return max(0, $score);
    }
}
```

## API Endpoints
```
# Queue Monitoring Management
GET    /monitoring/queues                    # Get all queue metrics
GET    /monitoring/queues/{name}            # Get specific queue metrics
GET    /monitoring/workers                   # Get all worker metrics
GET    /monitoring/workers/{id}             # Get specific worker metrics
GET    /monitoring/metrics                   # Get real-time metrics
GET    /monitoring/metrics/history          # Get historical metrics
GET    /monitoring/alerts                    # Get recent alerts
POST   /monitoring/alerts/acknowledge       # Acknowledge alert
GET    /monitoring/dashboards               # List dashboards
GET    /monitoring/dashboards/{name}        # Get dashboard data
POST   /monitoring/dashboards/{name}/config # Update dashboard config
GET    /monitoring/reports                  # Get monitoring reports
POST   /monitoring/reports/generate         # Generate custom report
GET    /monitoring/health                   # Get system health status
```

## Queue Metrics Response
```json
{
  "queues": {
    "default": {
      "name": "default",
      "depth": 154,
      "processing": 8,
      "processed": 12450,
      "failed": 23,
      "failure_rate": 0.0018,
      "processing_rate": 42.5,
      "average_latency": 1.2,
      "workers": {
        "active": 2,
        "idle": 0,
        "total": 2
      },
      "health": 95
    },
    "high_priority": {
      "name": "high_priority",
      "depth": 5,
      "processing": 1,
      "processed": 842,
      "failed": 1,
      "failure_rate": 0.0012,
      "processing_rate": 8.2,
      "average_latency": 0.5,
      "workers": {
        "active": 1,
        "idle": 0,
        "total": 1
      },
      "health": 98
    },
    "low_priority": {
      "name": "low_priority",
      "depth": 2450,
      "processing": 0,
      "processed": 3420,
      "failed": 15,
      "failure_rate": 0.0044,
      "processing_rate": 2.1,
      "average_latency": 45.2,
      "workers": {
        "active": 0,
        "idle": 1,
        "total": 1
      },
      "health": 85
    }
  },
  "timestamp": "2023-01-01T10:00:00Z",
  "system_health": 92
}
```

## Alert Response
```json
{
  "alerts": [
    {
      "id": "alert_5f4d4a4b4c4d4e4f50515253",
      "type": "threshold",
      "metric": "queue_depth",
      "queue": "low_priority",
      "current_value": 2450,
      "threshold": 1000,
      "severity": "warning",
      "message": "Queue depth exceeds 1000 jobs",
      "timestamp": "2023-01-01T09:59:30Z",
      "status": "active",
      "acknowledged_by": null,
      "acknowledged_at": null
    },
    {
      "id": "alert_5f4d4a4b4c4d4e4f50515254",
      "type": "anomaly",
      "metric": "processing_rate",
      "queue": "default",
      "current_value": 5.2,
      "expected_value": 42.5,
      "severity": "critical",
      "message": "Unusual drop in processing rate detected",
      "timestamp": "2023-01-01T09:58:45Z",
      "status": "acknowledged",
      "acknowledged_by": "admin",
      "acknowledged_at": "2023-01-01T09:59:00Z"
    }
  ]
}
```

## Evaluation Criteria
- [ ] Real-time queue metrics collection works
- [ ] Queue depth and length tracking accurate
- [ ] Processing rate monitoring functions
- [ ] Worker utilization metrics collected
- [ ] Job completion statistics tracked
- [ ] Failure rate tracking operational
- [ ] Latency measurements accurate
- [ ] Worker status monitoring works
- [ ] Resource utilization tracking functions
- [ ] Threshold-based alerts trigger correctly
- [ ] Anomaly detection identifies patterns
- [ ] Historical trend analysis provides insights
- [ ] Custom dashboard creation works
- [ ] Code is well-organized and documented
- [ ] Tests cover monitoring functionality

## Resources
- [Queue Monitoring Best Practices](https://www.rabbitmq.com/monitoring.html)
- [Monitoring Distributed Systems](https://sre.google/sre-book/monitoring-distributed-systems/)
- [Prometheus Monitoring](https://prometheus.io/)
- [Grafana Dashboards](https://grafana.com/)