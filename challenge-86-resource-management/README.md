# Challenge 86: Resource Management

## Description
In this challenge, you'll implement comprehensive resource management capabilities to optimize containerized application performance and efficiency. Resource management is essential for ensuring applications have the resources they need while preventing resource exhaustion and contention.

## Learning Objectives
- Understand resource management concepts and strategies
- Implement resource allocation and scheduling
- Create resource quotas and limits
- Handle resource monitoring and optimization
- Manage quality of service (QoS) classes
- Implement resource scaling and auto-scaling

## Requirements
Create a resource management system with the following features:

1. **Resource Allocation**:
   - CPU and memory allocation
   - Storage resource management
   - Network bandwidth allocation
   - GPU resource management
   - Resource requests and limits
   - Resource scheduling

2. **Quota Management**:
   - Namespace quotas
   - User quotas
   - Project quotas
   - Resource quota enforcement
   - Quota monitoring and alerts
   - Quota adjustment

3. **Monitoring and Optimization**:
   - Resource utilization monitoring
   - Performance metrics collection
   - Resource optimization recommendations
   - Bottleneck detection
   - Capacity planning
   - Cost optimization

4. **Advanced Features**:
   - Quality of Service (QoS) management
   - Resource scaling policies
   - Auto-scaling based on resource usage
   - Resource reservation and preemption
   - Resource sharing and isolation
   - Multi-tenancy resource management

## Features to Implement
- [ ] CPU and memory allocation
- [ ] Storage resource management
- [ ] Network bandwidth allocation
- [ ] GPU resource management
- [ ] Resource requests and limits
- [ ] Namespace quotas
- [ ] User quotas
- [ ] Resource utilization monitoring
- [ ] Performance metrics collection
- [ ] Quality of Service (QoS) management
- [ ] Resource scaling policies
- [ ] Auto-scaling based on resource usage
- [ ] Resource reservation and preemption

## Project Structure
```
backend-php/
├── src/
│   ├── Resources/
│   │   ├── ResourceManager.php
│   │   ├── Allocation/
│   │   │   ├── CpuAllocator.php
│   │   │   ├── MemoryAllocator.php
│   │   │   ├── StorageAllocator.php
│   │   │   └── NetworkAllocator.php
│   │   ├── Quotas/
│   │   │   ├── QuotaManager.php
│   │   │   ├── NamespaceQuota.php
│   │   │   ├── UserQuota.php
│   │   │   └── ProjectQuota.php
│   │   ├── Monitoring/
│   │   │   ├── ResourceMonitor.php
│   │   │   ├── MetricsCollector.php
│   │   │   └── PerformanceAnalyzer.php
│   │   ├── Optimization/
│   │   │   ├── Optimizer.php
│   │   │   ├── Recommender.php
│   │   │   └── BottleneckDetector.php
│   │   └── Scaling/
│   │       ├── ScalingManager.php
│   │       ├── AutoScaler.php
│   │       └── QosManager.php
│   └── Services/
├── public/
│   └── index.php
├── resources/
│   ├── quotas/
│   │   ├── namespace-quotas.yaml
│   │   ├── user-quotas.yaml
│   │   └── project-quotas.yaml
│   ├── policies/
│   │   ├── resource-policies.yaml
│   │   └── scaling-policies.yaml
│   ├── monitoring/
│   │   └── metrics/
│   └── optimization/
│       └── recommendations/
├── storage/
│   └── resources/
├── config/
│   └── resources.php
└── vendor/

frontend-react/
├── src/
│   ├── api/
│   │   └── resources.js
│   ├── components/
│   │   ├── Dashboard/
│   │   │   ├── ResourceOverview.jsx
│   │   │   ├── UtilizationCharts.jsx
│   │   │   └── QuotaStatus.jsx
│   │   ├── Allocation/
│   │   │   ├── ResourceAllocation.jsx
│   │   │   └── AllocationForm.jsx
│   │   ├── Quotas/
│   │   │   ├── QuotaList.jsx
│   │   │   └── QuotaDetail.jsx
│   │   └── Optimization/
│   │       ├── Recommendations.jsx
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

## Resource Allocation

### Resource Manager
Manage resource allocation and scheduling:
```php
class ResourceManager
{
    private $allocators;
    private $scheduler;
    private $quotaManager;
    
    public function allocateResources($request)
    {
        $namespace = $request['namespace'];
        $resources = $request['resources'];
        
        // Check quotas
        if (!$this->quotaManager->checkQuota($namespace, $resources)) {
            throw new QuotaExceededException("Resource quota exceeded for namespace: $namespace");
        }
        
        // Allocate resources
        $allocation = [];
        foreach ($resources as $resourceType => $amount) {
            if (isset($this->allocators[$resourceType])) {
                $allocation[$resourceType] = $this->allocators[$resourceType]->allocate(
                    $amount, 
                    $request['constraints'] ?? []
                );
            }
        }
        
        // Schedule the allocation
        $scheduled = $this->scheduler->schedule($allocation, $request['priority'] ?? 'normal');
        
        // Update quota usage
        $this->quotaManager->updateUsage($namespace, $resources);
        
        return $scheduled;
    }
    
    public function releaseResources($allocationId)
    {
        // Release allocated resources
        foreach ($this->allocators as $allocator) {
            $allocator->release($allocationId);
        }
        
        // Update quota usage
        $this->quotaManager->releaseUsage($allocationId);
    }
    
    public function getResourceAvailability()
    {
        $availability = [];
        foreach ($this->allocators as $type => $allocator) {
            $availability[$type] = $allocator->getAvailability();
        }
        return $availability;
    }
}
```

### CPU Allocator
Manage CPU resource allocation:
```php
class CpuAllocator
{
    private $totalCpus;
    private $allocatedCpus;
    private $cpuShares;
    
    public function __construct($totalCpus = 8)
    {
        $this->totalCpus = $totalCpus;
        $this->allocatedCpus = 0;
        $this->cpuShares = [];
    }
    
    public function allocate($cpuRequest, $constraints = [])
    {
        // Parse CPU request (e.g., "500m" for 0.5 CPU, "2" for 2 CPUs)
        $requestedCpus = $this->parseCpuRequest($cpuRequest);
        
        // Check if enough CPUs are available
        if ($this->allocatedCpus + $requestedCpus > $this->totalCpus) {
            throw new ResourceUnavailableException("Not enough CPU resources available");
        }
        
        // Apply constraints
        $allocation = $this->applyConstraints($requestedCpus, $constraints);
        
        // Reserve the CPUs
        $allocationId = uniqid('cpu_');
        $this->cpuShares[$allocationId] = $allocation;
        $this->allocatedCpus += $allocation['reserved'];
        
        return [
            'id' => $allocationId,
            'type' => 'cpu',
            'allocated' => $allocation['reserved'],
            'limits' => $allocation['limits'],
            'shares' => $allocation['shares']
        ];
    }
    
    public function release($allocationId)
    {
        if (isset($this->cpuShares[$allocationId])) {
            $this->allocatedCpus -= $this->cpuShares[$allocationId]['reserved'];
            unset($this->cpuShares[$allocationId]);
        }
    }
    
    public function getAvailability()
    {
        return [
            'total' => $this->totalCpus,
            'allocated' => $this->allocatedCpus,
            'available' => $this->totalCpus - $this->allocatedCpus,
            'utilization' => $this->totalCpus > 0 ? $this->allocatedCpus / $this->totalCpus : 0
        ];
    }
    
    private function parseCpuRequest($cpuRequest)
    {
        if (strpos($cpuRequest, 'm') !== false) {
            // MilliCPU format (e.g., "500m" = 0.5 CPU)
            return (int)str_replace('m', '', $cpuRequest) / 1000;
        }
        return (float)$cpuRequest;
    }
    
    private function applyConstraints($requestedCpus, $constraints)
    {
        $allocation = [
            'reserved' => $requestedCpus,
            'limits' => $requestedCpus,
            'shares' => 1024 // Default CPU shares
        ];
        
        // Apply CPU limits constraint
        if (isset($constraints['cpu_limits'])) {
            $allocation['limits'] = min($requestedCpus, $this->parseCpuRequest($constraints['cpu_limits']));
        }
        
        // Apply CPU shares constraint
        if (isset($constraints['cpu_shares'])) {
            $allocation['shares'] = $constraints['cpu_shares'];
        }
        
        return $allocation;
    }
}
```

## Quota Management

### Quota Manager
Manage resource quotas and enforcement:
```php
class QuotaManager
{
    private $quotas;
    private $usage;
    
    public function createQuota($scope, $quota)
    {
        $this->quotas[$scope] = $quota;
        $this->usage[$scope] = [
            'cpu' => 0,
            'memory' => 0,
            'storage' => 0,
            'pods' => 0
        ];
    }
    
    public function checkQuota($scope, $resources)
    {
        if (!isset($this->quotas[$scope])) {
            return true; // No quota defined
        }
        
        $quota = $this->quotas[$scope];
        $currentUsage = $this->usage[$scope];
        
        // Check each resource against quota limits
        foreach ($resources as $resource => $amount) {
            if (isset($quota[$resource]) && isset($currentUsage[$resource])) {
                $requestedAmount = $this->normalizeResourceAmount($resource, $amount);
                $currentAmount = $currentUsage[$resource];
                $quotaLimit = $this->normalizeResourceAmount($resource, $quota[$resource]);
                
                if ($currentAmount + $requestedAmount > $quotaLimit) {
                    return false;
                }
            }
        }
        
        return true;
    }
    
    public function updateUsage($scope, $resources)
    {
        if (!isset($this->usage[$scope])) {
            $this->usage[$scope] = [];
        }
        
        foreach ($resources as $resource => $amount) {
            $normalizedAmount = $this->normalizeResourceAmount($resource, $amount);
            if (!isset($this->usage[$scope][$resource])) {
                $this->usage[$scope][$resource] = 0;
            }
            $this->usage[$scope][$resource] += $normalizedAmount;
        }
    }
    
    public function releaseUsage($allocationId)
    {
        // In a real implementation, you would track which allocations
        // belong to which scopes and release accordingly
        // This is a simplified example
    }
    
    public function getQuotaStatus($scope)
    {
        if (!isset($this->quotas[$scope]) || !isset($this->usage[$scope])) {
            return null;
        }
        
        $quota = $this->quotas[$scope];
        $usage = $this->usage[$scope];
        
        $status = [];
        foreach ($quota as $resource => $limit) {
            $used = $usage[$resource] ?? 0;
            $limitNormalized = $this->normalizeResourceAmount($resource, $limit);
            
            $status[$resource] = [
                'used' => $used,
                'limit' => $limitNormalized,
                'percentage' => $limitNormalized > 0 ? ($used / $limitNormalized) * 100 : 0,
                'status' => $this->getQuotaStatusLevel($used, $limitNormalized)
            ];
        }
        
        return $status;
    }
    
    private function normalizeResourceAmount($resource, $amount)
    {
        switch ($resource) {
            case 'memory':
                return $this->parseMemoryAmount($amount);
            case 'storage':
                return $this->parseStorageAmount($amount);
            default:
                return (float)$amount;
        }
    }
    
    private function parseMemoryAmount($memory)
    {
        if (is_numeric($memory)) {
            return (float)$memory;
        }
        
        $units = [
            'Ki' => 1024,
            'Mi' => 1024 * 1024,
            'Gi' => 1024 * 1024 * 1024,
            'Ti' => 1024 * 1024 * 1024 * 1024,
            'K' => 1000,
            'M' => 1000 * 1000,
            'G' => 1000 * 1000 * 1000,
            'T' => 1000 * 1000 * 1000 * 1000
        ];
        
        foreach ($units as $unit => $multiplier) {
            if (strpos($memory, $unit) !== false) {
                $value = (float)str_replace($unit, '', $memory);
                return $value * $multiplier;
            }
        }
        
        return (float)$memory;
    }
    
    private function getQuotaStatusLevel($used, $limit)
    {
        $percentage = $limit > 0 ? ($used / $limit) * 100 : 0;
        
        if ($percentage >= 90) {
            return 'critical';
        } elseif ($percentage >= 75) {
            return 'warning';
        } elseif ($percentage >= 50) {
            return 'info';
        } else {
            return 'ok';
        }
    }
}
```

## Configuration Example
```php
[
    'resources' => [
        'cpu' => [
            'total' => 16,
            'allocator' => 'CpuAllocator',
            'scheduler' => 'FairScheduler',
            'overcommit' => false
        ],
        'memory' => [
            'total' => '32Gi',
            'allocator' => 'MemoryAllocator',
            'scheduler' => 'MemoryScheduler',
            'overcommit' => true
        ],
        'storage' => [
            'total' => '1Ti',
            'allocator' => 'StorageAllocator',
            'scheduler' => 'StorageScheduler',
            'overcommit' => false
        ],
        'gpu' => [
            'total' => 4,
            'allocator' => 'GpuAllocator',
            'scheduler' => 'GpuScheduler',
            'overcommit' => false
        ]
    ],
    'quotas' => [
        'namespaces' => [
            'default' => [
                'cpu' => '4',
                'memory' => '8Gi',
                'storage' => '100Gi',
                'pods' => 50
            ],
            'development' => [
                'cpu' => '2',
                'memory' => '4Gi',
                'storage' => '50Gi',
                'pods' => 20
            ],
            'production' => [
                'cpu' => '8',
                'memory' => '16Gi',
                'storage' => '500Gi',
                'pods' => 100
            ]
        ],
        'users' => [
            'developer' => [
                'cpu' => '1',
                'memory' => '2Gi',
                'pods' => 5
            ],
            'admin' => [
                'cpu' => '4',
                'memory' => '8Gi',
                'pods' => 20
            ]
        ],
        'projects' => [
            'web-app' => [
                'cpu' => '2',
                'memory' => '4Gi',
                'storage' => '10Gi'
            ],
            'data-processing' => [
                'cpu' => '6',
                'memory' => '12Gi',
                'storage' => '100Gi'
            ]
        ]
    ],
    'monitoring' => [
        'interval' => 30, // seconds
        'metrics' => [
            'cpu_utilization' => true,
            'memory_usage' => true,
            'disk_io' => true,
            'network_io' => true,
            'gpu_utilization' => true
        ],
        'thresholds' => [
            'cpu_high' => 80,
            'memory_high' => 85,
            'disk_io_high' => 90,
            'network_io_high' => 75
        ]
    ],
    'scaling' => [
        'auto_scaling' => true,
        'policies' => [
            'cpu_based' => [
                'metric' => 'cpu_utilization',
                'threshold' => 70,
                'scale_up_factor' => 1.5,
                'scale_down_factor' => 0.8,
                'cooldown' => 300 // seconds
            ],
            'memory_based' => [
                'metric' => 'memory_usage',
                'threshold' => 80,
                'scale_up_factor' => 1.5,
                'scale_down_factor' => 0.8,
                'cooldown' => 300 // seconds
            ]
        ]
    ],
    'qos' => [
        'classes' => [
            'guaranteed' => [
                'cpu_shares' => 1024,
                'memory_reservation' => 'guaranteed',
                'eviction_threshold' => 95
            ],
            'burstable' => [
                'cpu_shares' => 512,
                'memory_reservation' => 'soft',
                'eviction_threshold' => 85
            ],
            'best_effort' => [
                'cpu_shares' => 256,
                'memory_reservation' => 'none',
                'eviction_threshold' => 75
            ]
        ]
    ],
    'optimization' => [
        'enabled' => true,
        'interval' => 3600, // 1 hour
        'recommendations' => [
            'cpu_overallocation' => true,
            'memory_waste' => true,
            'storage_optimization' => true,
            'network_efficiency' => true
        ]
    ]
]
```

## Resource Monitoring
```php
class ResourceMonitor
{
    private $metricsCollector;
    private $analyzer;
    private $alertManager;
    
    public function collectMetrics()
    {
        $metrics = [
            'timestamp' => time(),
            'cpu' => $this->collectCpuMetrics(),
            'memory' => $this->collectMemoryMetrics(),
            'storage' => $this->collectStorageMetrics(),
            'network' => $this->collectNetworkMetrics(),
            'gpu' => $this->collectGpuMetrics()
        ];
        
        $this->storeMetrics($metrics);
        $this->analyzeMetrics($metrics);
        
        return $metrics;
    }
    
    private function collectCpuMetrics()
    {
        // In a real implementation, this would collect actual CPU metrics
        // from the system or container runtime
        return [
            'total' => 16,
            'used' => rand(8, 14),
            'idle' => rand(2, 8),
            'utilization' => rand(50, 85)
        ];
    }
    
    private function collectMemoryMetrics()
    {
        $total = 32 * 1024 * 1024 * 1024; // 32 GiB
        $used = rand(8, 28) * 1024 * 1024 * 1024; // 8-28 GiB
        
        return [
            'total' => $total,
            'used' => $used,
            'free' => $total - $used,
            'utilization' => ($used / $total) * 100
        ];
    }
    
    private function analyzeMetrics($metrics)
    {
        $alerts = [];
        
        // Check CPU utilization
        if ($metrics['cpu']['utilization'] > config('resources.monitoring.thresholds.cpu_high', 80)) {
            $alerts[] = new ResourceAlert(
                'high_cpu_utilization',
                'CPU utilization is high: ' . $metrics['cpu']['utilization'] . '%',
                'warning'
            );
        }
        
        // Check memory usage
        if ($metrics['memory']['utilization'] > config('resources.monitoring.thresholds.memory_high', 85)) {
            $alerts[] = new ResourceAlert(
                'high_memory_usage',
                'Memory usage is high: ' . $metrics['memory']['utilization'] . '%',
                'warning'
            );
        }
        
        // Send alerts
        foreach ($alerts as $alert) {
            $this->alertManager->sendAlert($alert);
        }
    }
}
```

## API Endpoints
```
# Resource Management
POST   /resources/allocate              # Allocate resources
DELETE /resources/allocate/{id}        # Release allocated resources
GET    /resources/availability         # Get resource availability
GET    /resources/utilization          # Get current resource utilization
GET    /resources/quotas               # List all quotas
GET    /resources/quotas/{scope}       # Get quota status for scope
POST   /resources/quotas/{scope}       # Update quota for scope
GET    /resources/monitoring/metrics    # Get resource metrics
GET    /resources/monitoring/alerts     # Get resource alerts
POST   /resources/scaling/scale         # Manually scale resources
GET    /resources/scaling/policies      # List scaling policies
POST   /resources/scaling/policies      # Create scaling policy
GET    /resources/scaling/policies/{name} # Get scaling policy
PUT    /resources/scaling/policies/{name} # Update scaling policy
DELETE /resources/scaling/policies/{name} # Delete scaling policy
GET    /resources/optimization/recommendations # Get optimization recommendations
POST   /resources/optimization/apply    # Apply optimization recommendations
GET    /resources/qos/classes           # List QoS classes
POST   /resources/qos/classes           # Create QoS class
GET    /resources/qos/classes/{name}    # Get QoS class
PUT    /resources/qos/classes/{name}    # Update QoS class
```

## Resource Allocation Response
```json
{
  "allocation": {
    "id": "alloc_5f4d4a4b4c4d4e4f50515253",
    "namespace": "production",
    "resources": {
      "cpu": {
        "id": "cpu_5f4d4a4b4c4d4e4f50515254",
        "type": "cpu",
        "allocated": 2,
        "limits": 2,
        "shares": 1024
      },
      "memory": {
        "id": "mem_5f4d4a4b4c4d4e4f50515255",
        "type": "memory",
        "allocated": "4294967296",
        "limits": "4294967296",
        "reservation": "4294967296"
      }
    },
    "status": "allocated",
    "created_at": "2023-01-01T10:00:00Z"
  }
}
```

## Quota Status Response
```json
{
  "scope": "production",
  "quotas": {
    "cpu": {
      "used": 6,
      "limit": 8,
      "percentage": 75,
      "status": "warning"
    },
    "memory": {
      "used": "12883775488",
      "limit": "17179869184",
      "percentage": 75,
      "status": "warning"
    },
    "storage": {
      "used": "21474836480",
      "limit": "53687091200",
      "percentage": 40,
      "status": "ok"
    },
    "pods": {
      "used": 25,
      "limit": 100,
      "percentage": 25,
      "status": "ok"
    }
  },
  "timestamp": "2023-01-01T10:00:00Z"
}
```

## Resource Metrics Response
```json
{
  "timestamp": "2023-01-01T10:00:00Z",
  "cpu": {
    "total": 16,
    "used": 12,
    "idle": 4,
    "utilization": 75
  },
  "memory": {
    "total": "34359738368",
    "used": "25769803776",
    "free": "8589934592",
    "utilization": 75
  },
  "storage": {
    "total": "1099511627776",
    "used": "329853488332",
    "free": "769658139444",
    "utilization": 30
  },
  "network": {
    "rx_bytes": 1073741824,
    "tx_bytes": 536870912,
    "rx_packets": 1000000,
    "tx_packets": 500000
  }
}
```

## Evaluation Criteria
- [ ] CPU and memory allocation functions correctly
- [ ] Storage resource management works
- [ ] Network bandwidth allocation operates
- [ ] GPU resource management functions
- [ ] Resource requests and limits enforced
- [ ] Namespace quotas managed properly
- [ ] User quotas enforced correctly
- [ ] Resource utilization monitoring accurate
- [ ] Performance metrics collection works
- [ ] Quality of Service (QoS) management implemented
- [ ] Resource scaling policies function
- [ ] Auto-scaling based on resource usage works
- [ ] Resource reservation and preemption operate
- [ ] Code is well-organized and documented
- [ ] Tests cover resource management functionality

## Resources
- [Kubernetes Resource Management](https://kubernetes.io/docs/concepts/configuration/manage-resources-containers/)
- [Resource Quotas](https://kubernetes.io/docs/concepts/policy/resource-quotas/)
- [Quality of Service](https://kubernetes.io/docs/tasks/configure-pod-container/quality-service-pod/)
- [Horizontal Pod Autoscaler](https://kubernetes.io/docs/tasks/run-application/horizontal-pod-autoscale/)