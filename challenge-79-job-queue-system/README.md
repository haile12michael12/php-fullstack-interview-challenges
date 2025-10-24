# Challenge 79: Job Queue System

## Description
In this challenge, you'll implement a robust job queue system that handles background processing of tasks. Job queues are essential for offloading time-consuming operations from the main request/response cycle, improving application responsiveness and scalability.

## Learning Objectives
- Understand job queue architectures and patterns
- Implement reliable job queuing and processing
- Handle job prioritization and scheduling
- Create job failure handling and retries
- Manage worker processes and scaling
- Implement job monitoring and metrics

## Requirements
Create a job queue system with the following features:

1. **Queue Management**:
   - Multiple queue support
   - Job enqueueing and dequeueing
   - Queue prioritization
   - Queue persistence
   - Queue monitoring
   - Queue statistics

2. **Job Processing**:
   - Job serialization and deserialization
   - Worker process management
   - Concurrent job processing
   - Job timeout handling
   - Graceful shutdown
   - Resource isolation

3. **Failure Handling**:
   - Job failure detection
   - Retry mechanisms
   - Dead letter queues
   - Failure notifications
   - Error logging
   - Manual retry support

4. **Advanced Features**:
   - Job scheduling and delayed execution
   - Job chaining and dependencies
   - Rate limiting
   - Batch job processing
   - Job progress tracking
   - Distributed worker support

## Features to Implement
- [ ] Multiple queue support
- [ ] Job enqueueing and dequeueing
- [ ] Queue prioritization
- [ ] Job serialization/deserialization
- [ ] Worker process management
- [ ] Concurrent job processing
- [ ] Job timeout handling
- [ ] Retry mechanisms
- [ ] Dead letter queues
- [ ] Job scheduling
- [ ] Job chaining
- [ ] Rate limiting
- [ ] Progress tracking
- [ ] Distributed workers

## Project Structure
```
backend-php/
├── src/
│   ├── Queue/
│   │   ├── JobQueue.php
│   │   ├── QueueManager.php
│   │   ├── Job.php
│   │   ├── Worker.php
│   │   ├── Processor.php
│   │   ├── Scheduler.php
│   │   ├── PriorityQueue.php
│   │   └── DelayedQueue.php
│   ├── Jobs/
│   │   ├── EmailJob.php
│   │   ├── ImageProcessingJob.php
│   │   ├── ReportGenerationJob.php
│   │   ├── DataExportJob.php
│   │   └── NotificationJob.php
│   ├── Workers/
│   │   ├── WorkerPool.php
│   │   ├── WorkerManager.php
│   │   └── ProcessManager.php
│   ├── Failure/
│   │   ├── FailureHandler.php
│   │   ├── RetryManager.php
│   │   ├── DeadLetterQueue.php
│   │   └── ErrorHandler.php
│   ├── Monitoring/
│   │   ├── QueueMonitor.php
│   │   ├── JobTracker.php
│   │   ├── MetricsCollector.php
│   │   └── AlertManager.php
│   └── Events/
│       ├── JobQueued.php
│       ├── JobProcessing.php
│       ├── JobProcessed.php
│       ├── JobFailed.php
│       └── WorkerStarted.php
├── public/
│   └── index.php
├── storage/
│   └── queues/
├── config/
│   └── queue.php
└── vendor/

frontend-react/
├── src/
│   ├── api/
│   │   └── queue.js
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

## Job Queue Architecture

### Job Class
Base job class with serialization support:
```php
abstract class Job
{
    protected $jobId;
    protected $queue;
    protected $priority;
    protected $attempts = 0;
    protected $maxAttempts = 3;
    protected $timeout = 60;
    protected $createdAt;
    protected $payload;
    
    public function __construct($payload = [])
    {
        $this->jobId = uniqid();
        $this->createdAt = time();
        $this->payload = $payload;
    }
    
    abstract public function handle();
    
    public function serialize()
    {
        return serialize([
            'class' => get_class($this),
            'data' => get_object_vars($this)
        ]);
    }
    
    public static function deserialize($serialized)
    {
        $data = unserialize($serialized);
        $job = new $data['class']();
        foreach ($data['data'] as $key => $value) {
            $job->$key = $value;
        }
        return $job;
    }
}
```

### Queue Manager
Manage multiple queues and job operations:
```php
class QueueManager
{
    private $queues = [];
    private $defaultQueue = 'default';
    
    public function push(Job $job, $queue = null)
    {
        $queue = $queue ?: $this->defaultQueue;
        $this->ensureQueueExists($queue);
        
        $job->setQueue($queue);
        $this->queues[$queue]->push($job);
        
        event(new JobQueued($job));
    }
    
    public function pop($queue = null)
    {
        $queue = $queue ?: $this->defaultQueue;
        $this->ensureQueueExists($queue);
        
        return $this->queues[$queue]->pop();
    }
    
    public function later($delay, Job $job, $queue = null)
    {
        $queue = $queue ?: $this->defaultQueue;
        $this->ensureQueueExists($queue);
        
        $job->setDelay($delay);
        $this->queues['delayed']->push($job);
    }
    
    public function getQueueStats($queue = null)
    {
        if ($queue) {
            return $this->queues[$queue]->getStats();
        }
        
        $stats = [];
        foreach ($this->queues as $name => $queue) {
            $stats[$name] = $queue->getStats();
        }
        return $stats;
    }
}
```

### Worker Process
Process jobs from queues:
```php
class Worker
{
    private $queueManager;
    private $shouldRun = true;
    private $maxJobs = 500;
    private $memoryLimit = 128;
    
    public function daemon($queue = null)
    {
        $jobsProcessed = 0;
        
        while ($this->shouldRun) {
            if ($this->memoryExceeded() || $jobsProcessed >= $this->maxJobs) {
                $this->stop();
                break;
            }
            
            $job = $this->queueManager->pop($queue);
            
            if ($job) {
                $this->process($job);
                $jobsProcessed++;
            } else {
                // No jobs available, sleep briefly
                sleep(1);
            }
        }
    }
    
    private function process(Job $job)
    {
        try {
            event(new JobProcessing($job));
            
            $job->handle();
            
            event(new JobProcessed($job));
        } catch (Exception $e) {
            $this->handleJobException($job, $e);
        }
    }
    
    private function handleJobException(Job $job, Exception $e)
    {
        $job->incrementAttempts();
        
        if ($job->attempts() < $job->maxAttempts()) {
            // Retry the job
            $this->queueManager->later(60 * $job->attempts(), $job);
        } else {
            // Move to dead letter queue
            $this->queueManager->deadLetter($job);
            event(new JobFailed($job, $e));
        }
        
        // Log the error
        error_log("Job failed: " . $e->getMessage());
    }
}
```

## Job Implementation Example
```php
class EmailJob extends Job
{
    private $to;
    private $subject;
    private $body;
    
    public function __construct($to, $subject, $body)
    {
        parent::__construct();
        $this->to = $to;
        $this->subject = $subject;
        $this->body = $body;
        $this->maxAttempts = 5;
        $this->timeout = 120;
    }
    
    public function handle()
    {
        // Simulate email sending
        sleep(2);
        
        // In a real implementation, you would send the email here
        $mailer = new Mailer();
        $mailer->send($this->to, $this->subject, $this->body);
        
        // Update progress if needed
        $this->updateProgress(100);
    }
}
```

## Configuration Example
```php
[
    'default' => 'redis',
    'connections' => [
        'redis' => [
            'driver' => 'redis',
            'host' => '127.0.0.1',
            'port' => 6379,
            'database' => 0,
            'queue' => 'default'
        ],
        'database' => [
            'driver' => 'database',
            'table' => 'jobs',
            'queue' => 'default'
        ],
        'sync' => [
            'driver' => 'sync'
        ]
    ],
    'queues' => [
        'default' => [
            'priority' => 10,
            'workers' => 2
        ],
        'high' => [
            'priority' => 1,
            'workers' => 1
        ],
        'low' => [
            'priority' => 100,
            'workers' => 1
        ]
    ],
    'worker' => [
        'sleep' => 3,
        'max_jobs' => 500,
        'max_time' => 3600,
        'memory_limit' => 128,
        'timeout' => 60
    ],
    'retry' => [
        'max_attempts' => 3,
        'backoff' => [60, 120, 240], // Exponential backoff
        'dead_letter_queue' => 'failed'
    ]
]
```

## Worker Management
```php
class WorkerManager
{
    private $workers = [];
    
    public function startWorkers($queue = null)
    {
        $queues = $queue ? [$queue] : array_keys(config('queue.queues'));
        
        foreach ($queues as $queueName) {
            $workerCount = config("queue.queues.{$queueName}.workers", 1);
            
            for ($i = 0; $i < $workerCount; $i++) {
                $worker = new Worker($queueName);
                $worker->start();
                $this->workers[] = $worker;
            }
        }
    }
    
    public function stopWorkers()
    {
        foreach ($this->workers as $worker) {
            $worker->stop();
        }
        $this->workers = [];
    }
    
    public function getWorkerStatus()
    {
        $status = [];
        foreach ($this->workers as $worker) {
            $status[] = $worker->getStatus();
        }
        return $status;
    }
}
```

## API Endpoints
```
# Job Queue Management
POST   /queue/jobs                # Enqueue a new job
GET    /queue/jobs/{id}          # Get job status
DELETE /queue/jobs/{id}          # Cancel a job
GET    /queue/queues             # List all queues
GET    /queue/queues/{name}      # Get queue details
POST   /queue/queues/{name}/retry # Retry failed jobs
GET    /queue/workers            # List all workers
POST   /queue/workers/start      # Start workers
POST   /queue/workers/stop       # Stop workers
GET    /queue/stats              # Get queue statistics
GET    /queue/failed             # List failed jobs
POST   /queue/failed/retry       # Retry failed jobs
DELETE /queue/failed/clear       # Clear failed jobs
```

## Queue Statistics Response
```json
{
  "queues": {
    "default": {
      "pending": 1542,
      "processing": 8,
      "processed": 12450,
      "failed": 23,
      "workers": 2,
      "priority": 10
    },
    "high": {
      "pending": 42,
      "processing": 2,
      "processed": 842,
      "failed": 1,
      "workers": 1,
      "priority": 1
    },
    "low": {
      "pending": 2450,
      "processing": 0,
      "processed": 3420,
      "failed": 15,
      "workers": 1,
      "priority": 100
    }
  },
  "workers": {
    "total": 4,
    "active": 4,
    "idle": 0
  },
  "performance": {
    "jobs_per_second": 42.5,
    "average_processing_time": "1.2s",
    "success_rate": 0.98
  }
}
```

## Job Status Response
```json
{
  "job_id": "5f4d4a4b4c4d4e4f50515253",
  "queue": "default",
  "status": "processing",
  "attempts": 1,
  "max_attempts": 3,
  "created_at": "2023-01-01T10:00:00Z",
  "started_at": "2023-01-01T10:00:05Z",
  "progress": 65,
  "payload": {
    "to": "user@example.com",
    "subject": "Welcome!",
    "body": "Welcome to our platform..."
  }
}
```

## Evaluation Criteria
- [ ] Multiple queue support functions correctly
- [ ] Job enqueueing and dequeueing work
- [ ] Queue prioritization respects priorities
- [ ] Job serialization/deserialization works
- [ ] Worker process management functions
- [ ] Concurrent job processing handles load
- [ ] Job timeout handling prevents stuck jobs
- [ ] Retry mechanisms recover from failures
- [ ] Dead letter queues capture failed jobs
- [ ] Job scheduling supports delayed execution
- [ ] Job chaining handles dependencies
- [ ] Rate limiting controls job flow
- [ ] Progress tracking monitors job status
- [ ] Distributed workers scale processing
- [ ] Code is well-organized and documented
- [ ] Tests cover job queue functionality

## Resources
- [Message Queue Patterns](https://www.enterpriseintegrationpatterns.com/patterns/messaging/MessageQueue.html)
- [Redis Queue](https://redis.io/topics/streams-intro)
- [RabbitMQ](https://www.rabbitmq.com/)
- [Beanstalkd](https://beanstalkd.github.io/)
- [Amazon SQS](https://aws.amazon.com/sqs/)