# Challenge 98: Performance Profiling Tools

## Description
In this challenge, you'll build advanced profiling tools for PHP applications. Performance profiling is essential for identifying bottlenecks, optimizing code, and ensuring applications meet performance requirements. You'll create comprehensive profiling tools that measure execution time, memory usage, database queries, HTTP requests, and other critical performance metrics.

## Learning Objectives
- Understand performance profiling concepts and techniques
- Build comprehensive profiling tools for PHP applications
- Implement timing and benchmarking utilities
- Create memory usage monitoring systems
- Develop database query profiling capabilities
- Build HTTP request/response profiling
- Implement custom profiling collectors
- Analyze and visualize performance data

## Requirements

### Core Features
1. **Timing and Benchmarking**
   - Implement high-resolution timers
   - Create benchmarking frameworks
   - Measure function execution times
   - Track code path performance
   - Compare performance across versions

2. **Resource Monitoring**
   - Monitor memory usage and allocation
   - Track CPU utilization
   - Measure I/O operations
   - Monitor network requests
   - Track garbage collection impact

3. **Database Profiling**
   - Capture query execution times
   - Analyze query performance
   - Identify slow queries
   - Track connection usage
   - Monitor database load

4. **HTTP Profiling**
   - Measure request/response times
   - Track HTTP status codes
   - Monitor bandwidth usage
   - Analyze API performance
   - Profile external service calls

### Implementation Details
1. **Profiler Interface**
   ```php
   interface ProfilerInterface
   {
       public function start(string $profileName): void;
       public function stop(string $profileName): ProfileResult;
       public function getResults(): array;
       public function reset(): void;
   }
   ```

2. **Collector Interface**
   ```php
   interface CollectorInterface
   {
       public function collect(): array;
       public function getName(): string;
       public function reset(): void;
   }
   ```

## Project Structure
```
challenge-98-performance-profiling/
├── backend-php/
│   ├── src/
│   │   ├── Profiling/
│   │   │   ├── Profiler.php
│   │   │   ├── ProfilerInterface.php
│   │   │   ├── ProfileResult.php
│   │   │   └── Exception/
│   │   │       ├── ProfilingException.php
│   │   │       └── CollectorException.php
│   │   ├── Collector/
│   │   │   ├── CollectorInterface.php
│   │   │   ├── TimerCollector.php
│   │   │   ├── MemoryCollector.php
│   │   │   ├── DatabaseCollector.php
│   │   │   ├── HttpCollector.php
│   │   │   └── CustomCollector.php
│   │   └── Visualization/
│   │       ├── DataExporter.php
│   │       ├── ChartGenerator.php
│   │       └── ReportGenerator.php
│   ├── config/
│   ├── public/
│   │   └── index.php
│   ├── tests/
│   ├── composer.json
│   ├── Dockerfile
│   └── README.md
├── frontend-react/
│   ├── src/
│   │   ├── components/
│   │   │   ├── ProfilerDashboard.jsx
│   │   │   ├── TimelineView.jsx
│   │   │   ├── MetricsChart.jsx
│   │   │   └── PerformanceReport.jsx
│   │   └── App.jsx
│   ├── package.json
│   ├── vite.config.js
│   ├── Dockerfile
│   └── README.md
├── docker-compose.yml
└── README.md
```

## Setup Instructions

### Prerequisites
- PHP 8.1+
- Composer
- Node.js 16+
- npm or yarn
- Docker (optional, for containerized deployment)
- Xdebug for profiling (optional)

### Backend Setup
1. Navigate to the [backend-php](file:///c%3A/projects/php-fullstack-challenges/challenge-98-performance-profiling/backend-php) directory
2. Install PHP dependencies:
   ```bash
   composer install
   ```
3. Enable profiling extensions (php.ini):
   ```ini
   extension=xdebug
   xdebug.mode=profile,trace
   xdebug.start_with_request=trigger
   ```
4. Start the development server:
   ```bash
   php public/index.php
   ```

### Frontend Setup
1. Navigate to the [frontend-react](file:///c%3A/projects/php-fullstack-challenges/challenge-98-performance-profiling/frontend-react) directory
2. Install JavaScript dependencies:
   ```bash
   npm install
   ```
3. Start the development server:
   ```bash
   npm run dev
   ```

### Docker Setup
1. From the challenge root directory, run:
   ```bash
   docker-compose up -d
   ```
2. Access the application at `http://localhost:3000`

## API Endpoints

### Profiling Tools
- **POST** `/profile/start` - Start a new profiling session
- **POST** `/profile/stop` - Stop profiling and get results
- **GET** `/profile/results` - Get profiling results
- **GET** `/profile/export` - Export profiling data
- **POST** `/profile/reset` - Reset profiling data

## Implementation Details

### Core Profiling System

1. **Main Profiler Class**
   ```php
   class Profiler implements ProfilerInterface
   {
       private array $profiles = [];
       private array $collectors = [];
       private bool $enabled = false;
       
       public function __construct(array $collectors = [])
       {
           foreach ($collectors as $collector) {
               $this->addCollector($collector);
           }
       }
       
       public function addCollector(CollectorInterface $collector): void
       {
           $this->collectors[$collector->getName()] = $collector;
       }
       
       public function start(string $profileName): void
       {
           if (!$this->enabled) {
               return;
           }
           
           $this->profiles[$profileName] = [
               'start_time' => microtime(true),
               'start_memory' => memory_get_usage(true),
               'collectors' => []
           ];
           
           // Start all collectors
           foreach ($this->collectors as $collector) {
               $collector->start($profileName);
           }
       }
       
       public function stop(string $profileName): ProfileResult
       {
           if (!isset($this->profiles[$profileName])) {
               throw new InvalidArgumentException("Profile {$profileName} not started");
           }
           
           $profile = $this->profiles[$profileName];
           $endTime = microtime(true);
           $endMemory = memory_get_usage(true);
           
           // Collect data from all collectors
           $collectorData = [];
           foreach ($this->collectors as $collector) {
               $collectorData[$collector->getName()] = $collector->collect($profileName);
               $collector->stop($profileName);
           }
           
           $result = new ProfileResult(
               $profileName,
               $endTime - $profile['start_time'],
               $endMemory - $profile['start_memory'],
               $collectorData
           );
           
           unset($this->profiles[$profileName]);
           
           return $result;
       }
       
       public function getResults(): array
       {
           return array_values($this->profiles);
       }
       
       public function reset(): void
       {
           $this->profiles = [];
           foreach ($this->collectors as $collector) {
               $collector->reset();
           }
       }
       
       public function enable(): void
       {
           $this->enabled = true;
       }
       
       public function disable(): void
       {
           $this->enabled = false;
       }
   }
   ```

2. **Profile Result Class**
   ```php
   class ProfileResult
   {
       public function __construct(
           private string $name,
           private float $duration,
           private int $memoryUsage,
           private array $collectorData
       ) {}
       
       public function getName(): string
       {
           return $this->name;
       }
       
       public function getDuration(): float
       {
           return $this->duration;
       }
       
       public function getMemoryUsage(): int
       {
           return $this->memoryUsage;
       }
       
       public function getCollectorData(): array
       {
           return $this->collectorData;
       }
       
       public function toArray(): array
       {
           return [
               'name' => $this->name,
               'duration' => $this->duration,
               'memory_usage' => $this->memoryUsage,
               'collector_data' => $this->collectorData,
               'timestamp' => time()
           ];
       }
   }
   ```

3. **Timer Collector**
   ```php
   class TimerCollector implements CollectorInterface
   {
       private array $timers = [];
       private array $measurements = [];
       
       public function getName(): string
       {
           return 'timer';
       }
       
       public function start(string $profileName, string $timerName = 'default'): void
       {
           if (!isset($this->timers[$profileName])) {
               $this->timers[$profileName] = [];
           }
           
           $this->timers[$profileName][$timerName] = microtime(true);
       }
       
       public function stop(string $profileName, string $timerName = 'default'): float
       {
           if (!isset($this->timers[$profileName][$timerName])) {
               throw new InvalidArgumentException("Timer {$timerName} not started for profile {$profileName}");
           }
           
           $duration = microtime(true) - $this->timers[$profileName][$timerName];
           
           if (!isset($this->measurements[$profileName])) {
               $this->measurements[$profileName] = [];
           }
           
           if (!isset($this->measurements[$profileName][$timerName])) {
               $this->measurements[$profileName][$timerName] = [];
           }
           
           $this->measurements[$profileName][$timerName][] = $duration;
           unset($this->timers[$profileName][$timerName]);
           
           return $duration;
       }
       
       public function collect(string $profileName): array
       {
           if (!isset($this->measurements[$profileName])) {
               return [];
           }
           
           $results = [];
           foreach ($this->measurements[$profileName] as $timerName => $measurements) {
               $results[$timerName] = [
                   'count' => count($measurements),
                   'total_time' => array_sum($measurements),
                   'average_time' => array_sum($measurements) / count($measurements),
                   'min_time' => min($measurements),
                   'max_time' => max($measurements),
                   'measurements' => $measurements
               ];
           }
           
           return $results;
       }
       
       public function reset(): void
       {
           $this->timers = [];
           $this->measurements = [];
       }
   }
   ```

4. **Memory Collector**
   ```php
   class MemoryCollector implements CollectorInterface
   {
       private array $checkpoints = [];
       
       public function getName(): string
       {
           return 'memory';
       }
       
       public function checkpoint(string $profileName, string $checkpointName): void
       {
           if (!isset($this->checkpoints[$profileName])) {
               $this->checkpoints[$profileName] = [];
           }
           
           $this->checkpoints[$profileName][$checkpointName] = [
               'memory_usage' => memory_get_usage(true),
               'real_memory_usage' => memory_get_usage(false),
               'peak_memory_usage' => memory_get_peak_usage(true),
               'timestamp' => microtime(true)
           ];
       }
       
       public function collect(string $profileName): array
       {
           if (!isset($this->checkpoints[$profileName])) {
               return [];
           }
           
           $checkpoints = $this->checkpoints[$profileName];
           $results = [];
           
           $previous = null;
           foreach ($checkpoints as $name => $data) {
               $results[$name] = $data;
               
               if ($previous !== null) {
                   $results[$name]['diff'] = [
                       'memory' => $data['memory_usage'] - $previous['memory_usage'],
                       'real_memory' => $data['real_memory_usage'] - $previous['real_memory_usage'],
                       'time' => $data['timestamp'] - $previous['timestamp']
                   ];
               }
               
               $previous = $data;
           }
           
           return $results;
       }
       
       public function reset(): void
       {
           $this->checkpoints = [];
       }
   }
   ```

5. **Database Collector**
   ```php
   class DatabaseCollector implements CollectorInterface
   {
       private array $queries = [];
       private array $connections = [];
       
       public function getName(): string
       {
           return 'database';
       }
       
       public function trackQuery(string $profileName, string $sql, array $params = [], float $duration = 0.0): void
       {
           if (!isset($this->queries[$profileName])) {
               $this->queries[$profileName] = [];
           }
           
           $this->queries[$profileName][] = [
               'sql' => $sql,
               'params' => $params,
               'duration' => $duration,
               'timestamp' => microtime(true),
               'backtrace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5)
           ];
       }
       
       public function trackConnection(string $profileName, string $connectionName, string $status): void
       {
           if (!isset($this->connections[$profileName])) {
               $this->connections[$profileName] = [];
           }
           
           $this->connections[$profileName][] = [
               'connection' => $connectionName,
               'status' => $status,
               'timestamp' => microtime(true)
           ];
       }
       
       public function collect(string $profileName): array
       {
           $queries = $this->queries[$profileName] ?? [];
           $connections = $this->connections[$profileName] ?? [];
           
           // Analyze queries
           $slowQueries = array_filter($queries, function($query) {
               return $query['duration'] > 0.1; // Slow query threshold
           });
           
           $queryStats = [
               'total_queries' => count($queries),
               'slow_queries' => count($slowQueries),
               'total_duration' => array_sum(array_column($queries, 'duration')),
               'average_duration' => count($queries) > 0 ? array_sum(array_column($queries, 'duration')) / count($queries) : 0,
               'queries' => $queries,
               'connections' => $connections
           ];
           
           return $queryStats;
       }
       
       public function reset(): void
       {
           $this->queries = [];
           $this->connections = [];
       }
   }
   ```

6. **HTTP Collector**
   ```php
   class HttpCollector implements CollectorInterface
   {
       private array $requests = [];
       
       public function getName(): string
       {
           return 'http';
       }
       
       public function trackRequest(string $profileName, string $url, string $method, array $headers = []): void
       {
           if (!isset($this->requests[$profileName])) {
               $this->requests[$profileName] = [];
           }
           
           $this->requests[$profileName][] = [
               'url' => $url,
               'method' => $method,
               'headers' => $headers,
               'start_time' => microtime(true),
               'status_code' => null,
               'duration' => null,
               'response_size' => null
           ];
       }
       
       public function trackResponse(string $profileName, int $statusCode, int $responseSize = 0): void
       {
           if (!isset($this->requests[$profileName]) || empty($this->requests[$profileName])) {
               return;
           }
           
           // Get the last request
           $index = count($this->requests[$profileName]) - 1;
           if ($index >= 0) {
               $this->requests[$profileName][$index]['status_code'] = $statusCode;
               $this->requests[$profileName][$index]['duration'] = microtime(true) - $this->requests[$profileName][$index]['start_time'];
               $this->requests[$profileName][$index]['response_size'] = $responseSize;
           }
       }
       
       public function collect(string $profileName): array
       {
           $requests = $this->requests[$profileName] ?? [];
           
           // Analyze requests
           $slowRequests = array_filter($requests, function($request) {
               return isset($request['duration']) && $request['duration'] > 1.0; // Slow request threshold
           });
           
           $errorRequests = array_filter($requests, function($request) {
               return isset($request['status_code']) && $request['status_code'] >= 400;
           });
           
           $requestStats = [
               'total_requests' => count($requests),
               'slow_requests' => count($slowRequests),
               'error_requests' => count($errorRequests),
               'total_duration' => array_sum(array_column(array_filter($requests, function($r) { return isset($r['duration']); }), 'duration')),
               'average_duration' => count(array_filter($requests, function($r) { return isset($r['duration']); })) > 0 ? 
                   array_sum(array_column(array_filter($requests, function($r) { return isset($r['duration']); }), 'duration')) / 
                   count(array_filter($requests, function($r) { return isset($r['duration']); })) : 0,
               'total_response_size' => array_sum(array_column(array_filter($requests, function($r) { return isset($r['response_size']); }), 'response_size')),
               'requests' => $requests
           ];
           
           return $requestStats;
       }
       
       public function reset(): void
       {
           $this->requests = [];
       }
   }
   ```

7. **Data Exporter**
   ```php
   class DataExporter
   {
       public function exportToJSON(array $data): string
       {
           return json_encode($data, JSON_PRETTY_PRINT);
       }
       
       public function exportToCSV(array $data): string
       {
           if (empty($data)) {
               return '';
           }
           
           $csv = '';
           $headers = array_keys(reset($data));
           $csv .= implode(',', $headers) . "\n";
           
           foreach ($data as $row) {
               $csv .= implode(',', array_map(function($value) {
                   if (is_array($value)) {
                       return '"' . str_replace('"', '""', json_encode($value)) . '"';
                   }
                   return '"' . str_replace('"', '""', (string)$value) . '"';
               }, $row)) . "\n";
           }
           
           return $csv;
       }
       
       public function exportToHTML(array $data): string
       {
           $html = '<!DOCTYPE html>
           <html>
           <head>
               <title>Performance Profile</title>
               <style>
                   body { font-family: Arial, sans-serif; }
                   table { border-collapse: collapse; width: 100%; }
                   th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                   th { background-color: #f2f2f2; }
               </style>
           </head>
           <body>';
           
           $html .= '<h1>Performance Profile Report</h1>';
           
           foreach ($data as $profileName => $profileData) {
               $html .= "<h2>Profile: {$profileName}</h2>";
               $html .= '<table>';
               
               foreach ($profileData as $key => $value) {
                   $html .= '<tr>';
                   $html .= "<th>{$key}</th>";
                   $html .= '<td>' . (is_array($value) ? json_encode($value) : $value) . '</td>';
                   $html .= '</tr>';
               }
               
               $html .= '</table>';
           }
           
           $html .= '</body></html>';
           
           return $html;
       }
   }
   ```

### Frontend Interface
The React frontend should:
1. Display real-time profiling data
2. Visualize performance metrics in charts
3. Show detailed profiling reports
4. Provide filtering and sorting capabilities
5. Offer export functionality for profiling data
6. Include educational content about performance profiling

## Evaluation Criteria
1. **Correctness** (30%)
   - Accurate timing and measurement
   - Proper data collection
   - Correct profiling results

2. **Comprehensiveness** (25%)
   - Complete profiling capabilities
   - Multiple collector types
   - Rich data analysis

3. **Performance** (20%)
   - Minimal overhead from profiling
   - Efficient data collection
   - Proper resource management

4. **Usability** (15%)
   - Intuitive API design
   - Clear documentation
   - Easy integration

5. **Visualization** (10%)
   - Clear data presentation
   - Helpful charts and graphs
   - Export capabilities

## Resources
1. [Xdebug Profiling](https://xdebug.org/docs/profiler)
2. [PHP Performance Tuning](https://www.php.net/manual/en/features.commandline.webserver.php)
3. [Blackfire Profiler](https://blackfire.io/)
4. [Tideways Profiler](https://tideways.com/)
5. [Performance Profiling Techniques](https://en.wikipedia.org/wiki/Profiling_(computer_programming))
6. [APM Tools Comparison](https://www.datadoghq.com/blog/php-apm-comparison/)
7. [PHP Benchmarking](https://www.php.net/manual/en/function.microtime.php)

## Stretch Goals
1. Implement distributed tracing capabilities
2. Create real-time performance dashboards
3. Build machine learning-based anomaly detection
4. Implement custom profiling annotations
5. Create performance regression testing tools
6. Develop advanced data visualization features
7. Implement profiling data compression and storage