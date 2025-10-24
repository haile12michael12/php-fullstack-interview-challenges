# Challenge 83: Docker Orchestration

## Description
In this challenge, you'll implement Docker orchestration capabilities to manage containerized applications at scale. Docker orchestration is essential for deploying, scaling, and managing containerized applications in production environments.

## Learning Objectives
- Understand Docker orchestration concepts and tools
- Implement container lifecycle management
- Create service discovery and load balancing
- Handle container scaling and auto-scaling
- Manage container networking and storage
- Implement health checks and monitoring

## Requirements
Create a Docker orchestration system with the following features:

1. **Container Management**:
   - Container deployment and provisioning
   - Container lifecycle management
   - Image management and versioning
   - Resource allocation and constraints
   - Container scheduling
   - Rollback capabilities

2. **Service Management**:
   - Service definition and deployment
   - Service discovery and registration
   - Load balancing and routing
   - Service scaling and auto-scaling
   - Service updates and rollbacks
   - Service health monitoring

3. **Networking and Storage**:
   - Container networking configuration
   - Volume management and persistence
   - Network policies and security
   - Service mesh integration
   - Ingress and egress control
   - DNS and name resolution

4. **Advanced Features**:
   - Cluster management and federation
   - Resource optimization and quotas
   - Security and access control
   - Logging and monitoring integration
   - Backup and disaster recovery
   - Multi-environment deployment

## Features to Implement
- [ ] Container deployment and provisioning
- [ ] Container lifecycle management
- [ ] Image management and versioning
- [ ] Resource allocation and constraints
- [ ] Service definition and deployment
- [ ] Service discovery and registration
- [ ] Load balancing and routing
- [ ] Service scaling and auto-scaling
- [ ] Container networking configuration
- [ ] Volume management and persistence
- [ ] Health checks and monitoring
- [ ] Cluster management
- [ ] Security and access control

## Project Structure
```
backend-php/
├── src/
│   ├── Orchestration/
│   │   ├── DockerOrchestrator.php
│   │   ├── Container/
│   │   │   ├── ContainerManager.php
│   │   │   ├── ContainerDefinition.php
│   │   │   ├── ImageManager.php
│   │   │   └── LifecycleManager.php
│   │   ├── Services/
│   │   │   ├── ServiceManager.php
│   │   │   ├── ServiceDefinition.php
│   │   │   ├── ServiceDiscovery.php
│   │   │   └── LoadBalancer.php
│   │   ├── Networking/
│   │   │   ├── NetworkManager.php
│   │   │   ├── VolumeManager.php
│   │   │   └── ServiceMesh.php
│   │   ├── Scaling/
│   │   │   ├── ScaleManager.php
│   │   │   ├── AutoScaler.php
│   │   │   └── ResourceManager.php
│   │   └── Cluster/
│   │       ├── ClusterManager.php
│   │       ├── NodeManager.php
│   │       └── FederationManager.php
│   ├── Docker/
│   │   ├── DockerClient.php
│   │   ├── Container.php
│   │   ├── Image.php
│   │   └── Network.php
│   └── Services/
├── public/
│   └── index.php
├── docker/
│   ├── docker-compose.yml
│   ├── Dockerfile
│   ├── nginx.conf
│   └── scripts/
├── storage/
│   └── orchestration/
├── config/
│   └── orchestration.php
└── vendor/

frontend-react/
├── src/
│   ├── api/
│   │   └── orchestration.js
│   ├── components/
│   │   ├── Dashboard/
│   │   │   ├── ClusterStatus.jsx
│   │   │   ├── ServiceList.jsx
│   │   │   └── ContainerMetrics.jsx
│   │   ├── Services/
│   │   │   ├── ServiceDetail.jsx
│   │   │   └── ServiceDeployment.jsx
│   │   └── Containers/
│   │       ├── ContainerList.jsx
│   │       └── ContainerDetail.jsx
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

## Container Management

### Container Definition
Define container specifications:
```php
class ContainerDefinition
{
    private $name;
    private $image;
    private $ports;
    private $environment;
    private $volumes;
    private $resources;
    private $healthCheck;
    private $restartPolicy;
    
    public function __construct($config)
    {
        $this->name = $config['name'];
        $this->image = $config['image'];
        $this->ports = $config['ports'] ?? [];
        $this->environment = $config['environment'] ?? [];
        $this->volumes = $config['volumes'] ?? [];
        $this->resources = $config['resources'] ?? [];
        $this->healthCheck = $config['health_check'] ?? [];
        $this->restartPolicy = $config['restart_policy'] ?? 'unless-stopped';
    }
    
    public function toDockerConfig()
    {
        return [
            'image' => $this->image,
            'ports' => $this->formatPorts(),
            'environment' => $this->environment,
            'volumes' => $this->volumes,
            'restart' => $this->restartPolicy,
            'healthcheck' => $this->formatHealthCheck(),
            'deploy' => $this->formatResources()
        ];
    }
    
    private function formatPorts()
    {
        $ports = [];
        foreach ($this->ports as $port) {
            $ports[] = "{$port['host']}:{$port['container']}";
        }
        return $ports;
    }
    
    private function formatHealthCheck()
    {
        if (empty($this->healthCheck)) {
            return null;
        }
        
        return [
            'test' => $this->healthCheck['command'],
            'interval' => $this->healthCheck['interval'] ?? '30s',
            'timeout' => $this->healthCheck['timeout'] ?? '10s',
            'retries' => $this->healthCheck['retries'] ?? 3,
            'start_period' => $this->healthCheck['start_period'] ?? '40s'
        ];
    }
    
    private function formatResources()
    {
        if (empty($this->resources)) {
            return null;
        }
        
        return [
            'resources' => [
                'limits' => [
                    'cpus' => $this->resources['cpus'] ?? '0.5',
                    'memory' => $this->resources['memory'] ?? '512M'
                ],
                'reservations' => [
                    'cpus' => $this->resources['reserved_cpus'] ?? '0.25',
                    'memory' => $this->resources['reserved_memory'] ?? '256M'
                ]
            ]
        ];
    }
}
```

### Container Manager
Manage container lifecycle:
```php
class ContainerManager
{
    private $dockerClient;
    private $definitionManager;
    
    public function deploy(ContainerDefinition $definition)
    {
        $config = $definition->toDockerConfig();
        
        // Pull the image if it doesn't exist
        $this->dockerClient->pullImage($definition->getImage());
        
        // Create and start the container
        $containerId = $this->dockerClient->createContainer(
            $definition->getName(),
            $config
        );
        
        $this->dockerClient->startContainer($containerId);
        
        // Register with service discovery
        $this->registerWithDiscovery($definition);
        
        return $containerId;
    }
    
    public function scale($serviceName, $replicas)
    {
        $currentContainers = $this->getContainersByService($serviceName);
        $currentCount = count($currentContainers);
        
        if ($replicas > $currentCount) {
            // Scale up
            $this->scaleUp($serviceName, $replicas - $currentCount);
        } elseif ($replicas < $currentCount) {
            // Scale down
            $this->scaleDown($serviceName, $currentCount - $replicas);
        }
    }
    
    public function update($serviceName, ContainerDefinition $newDefinition)
    {
        // Implement rolling update strategy
        $containers = $this->getContainersByService($serviceName);
        
        foreach ($containers as $container) {
            // Stop and remove old container
            $this->dockerClient->stopContainer($container->getId());
            $this->dockerClient->removeContainer($container->getId());
            
            // Deploy new container
            $this->deploy($newDefinition);
            
            // Wait for health check to pass
            $this->waitForHealthy($newDefinition->getName());
        }
    }
    
    public function rollback($serviceName, $previousVersion)
    {
        // Retrieve previous container definition
        $previousDefinition = $this->definitionManager->getVersion(
            $serviceName, 
            $previousVersion
        );
        
        // Perform rollback
        $this->update($serviceName, $previousDefinition);
    }
    
    private function scaleUp($serviceName, $count)
    {
        $definition = $this->definitionManager->getLatest($serviceName);
        
        for ($i = 0; $i < $count; $i++) {
            // Create unique name for replica
            $replicaName = $serviceName . '-' . uniqid();
            $definition->setName($replicaName);
            
            $this->deploy($definition);
        }
    }
    
    private function scaleDown($serviceName, $count)
    {
        $containers = $this->getContainersByService($serviceName);
        
        // Remove the specified number of containers
        for ($i = 0; $i < $count; $i++) {
            if (isset($containers[$i])) {
                $container = $containers[$i];
                $this->dockerClient->stopContainer($container->getId());
                $this->dockerClient->removeContainer($container->getId());
            }
        }
    }
}
```

## Service Management
```php
class ServiceManager
{
    private $containerManager;
    private $serviceDiscovery;
    private $loadBalancer;
    
    public function createService(ServiceDefinition $definition)
    {
        // Deploy initial containers
        $this->containerManager->deploy($definition->getContainerDefinition());
        
        // Register service
        $this->serviceDiscovery->registerService($definition);
        
        // Configure load balancing
        $this->loadBalancer->addService($definition);
        
        return $definition->getName();
    }
    
    public function scaleService($serviceName, $replicas)
    {
        // Scale the underlying containers
        $this->containerManager->scale($serviceName, $replicas);
        
        // Update service discovery
        $this->serviceDiscovery->updateServiceReplicas($serviceName, $replicas);
        
        // Update load balancer
        $this->loadBalancer->updateServiceReplicas($serviceName, $replicas);
    }
    
    public function updateService($serviceName, ServiceDefinition $newDefinition)
    {
        // Perform rolling update of containers
        $this->containerManager->update($serviceName, $newDefinition);
        
        // Update service discovery
        $this->serviceDiscovery->updateService($serviceName, $newDefinition);
        
        // Update load balancer
        $this->loadBalancer->updateService($serviceName, $newDefinition);
    }
    
    public function getServiceStatus($serviceName)
    {
        $containers = $this->containerManager->getContainersByService($serviceName);
        $healthy = 0;
        $unhealthy = 0;
        
        foreach ($containers as $container) {
            if ($container->isHealthy()) {
                $healthy++;
            } else {
                $unhealthy++;
            }
        }
        
        return [
            'name' => $serviceName,
            'desired_replicas' => count($containers),
            'running_replicas' => $healthy + $unhealthy,
            'healthy_replicas' => $healthy,
            'unhealthy_replicas' => $unhealthy,
            'status' => $unhealthy > 0 ? 'degraded' : 'healthy'
        ];
    }
}
```

## Configuration Example
```php
[
    'cluster' => [
        'name' => 'production-cluster',
        'nodes' => [
            'manager1' => [
                'host' => 'manager1.internal',
                'port' => 2377,
                'role' => 'manager'
            ],
            'manager2' => [
                'host' => 'manager2.internal',
                'port' => 2377,
                'role' => 'manager'
            ],
            'worker1' => [
                'host' => 'worker1.internal',
                'port' => 2377,
                'role' => 'worker'
            ],
            'worker2' => [
                'host' => 'worker2.internal',
                'port' => 2377,
                'role' => 'worker'
            ]
        ]
    ],
    'services' => [
        'web' => [
            'image' => 'nginx:latest',
            'replicas' => 3,
            'ports' => [
                ['host' => 80, 'container' => 80],
                ['host' => 443, 'container' => 443]
            ],
            'environment' => [
                'ENV' => 'production'
            ],
            'volumes' => [
                '/var/www:/usr/share/nginx/html'
            ],
            'resources' => [
                'cpus' => '0.5',
                'memory' => '512M'
            ],
            'health_check' => [
                'command' => ['CMD', 'curl', '-f', 'http://localhost'],
                'interval' => '30s',
                'timeout' => '10s',
                'retries' => 3
            ]
        ],
        'api' => [
            'image' => 'myapp/api:latest',
            'replicas' => 2,
            'ports' => [
                ['host' => 8080, 'container' => 8080]
            ],
            'environment' => [
                'DB_HOST' => 'database',
                'REDIS_HOST' => 'redis'
            ],
            'resources' => [
                'cpus' => '1.0',
                'memory' => '1G'
            ],
            'health_check' => [
                'command' => ['CMD', 'curl', '-f', 'http://localhost:8080/health'],
                'interval' => '15s',
                'timeout' => '5s',
                'retries' => 3
            ]
        ],
        'database' => [
            'image' => 'mysql:8.0',
            'replicas' => 1,
            'ports' => [
                ['host' => 3306, 'container' => 3306]
            ],
            'environment' => [
                'MYSQL_ROOT_PASSWORD' => 'secretpassword',
                'MYSQL_DATABASE' => 'myapp'
            ],
            'volumes' => [
                'mysql-data:/var/lib/mysql'
            ],
            'resources' => [
                'cpus' => '2.0',
                'memory' => '2G'
            ]
        ]
    ],
    'networking' => [
        'networks' => [
            'frontend' => [
                'driver' => 'bridge',
                'subnet' => '172.20.0.0/16'
            ],
            'backend' => [
                'driver' => 'bridge',
                'subnet' => '172.21.0.0/16'
            ]
        ],
        'volumes' => [
            'mysql-data' => [
                'driver' => 'local'
            ]
        ]
    ],
    'scaling' => [
        'auto_scaling' => true,
        'metrics' => [
            'cpu_threshold' => 75,
            'memory_threshold' => 80,
            'request_rate_threshold' => 1000
        ],
        'policies' => [
            'scale_up' => [
                'cooldown' => 300, // 5 minutes
                'max_replicas' => 10
            ],
            'scale_down' => [
                'cooldown' => 600, // 10 minutes
                'min_replicas' => 1
            ]
        ]
    ]
]
```

## Auto Scaling Implementation
```php
class AutoScaler
{
    private $scaleManager;
    private $metricsCollector;
    private $policies;
    
    public function checkAndScale()
    {
        $services = $this->scaleManager->getServices();
        
        foreach ($services as $service) {
            $metrics = $this->metricsCollector->getServiceMetrics($service->getName());
            $currentReplicas = $service->getReplicas();
            
            $recommendedReplicas = $this->calculateRecommendedReplicas(
                $service, 
                $metrics, 
                $currentReplicas
            );
            
            if ($recommendedReplicas !== $currentReplicas) {
                $this->scaleManager->scaleService($service->getName(), $recommendedReplicas);
            }
        }
    }
    
    private function calculateRecommendedReplicas($service, $metrics, $currentReplicas)
    {
        $policy = $this->policies[$service->getName()] ?? $this->policies['default'];
        $recommended = $currentReplicas;
        
        // Check CPU usage
        if (isset($metrics['cpu_usage'])) {
            if ($metrics['cpu_usage'] > $policy['cpu_threshold']) {
                $recommended = min($currentReplicas + 1, $policy['max_replicas']);
            } elseif ($metrics['cpu_usage'] < $policy['cpu_threshold'] * 0.5) {
                $recommended = max($currentReplicas - 1, $policy['min_replicas']);
            }
        }
        
        // Check memory usage
        if (isset($metrics['memory_usage'])) {
            if ($metrics['memory_usage'] > $policy['memory_threshold']) {
                $recommended = min($recommended + 1, $policy['max_replicas']);
            }
        }
        
        // Check request rate
        if (isset($metrics['request_rate'])) {
            if ($metrics['request_rate'] > $policy['request_rate_threshold']) {
                $recommended = min($recommended + 1, $policy['max_replicas']);
            }
        }
        
        return $recommended;
    }
}
```

## API Endpoints
```
# Docker Orchestration Management
POST   /orchestration/services              # Create new service
GET    /orchestration/services             # List all services
GET    /orchestration/services/{name}      # Get service details
PUT    /orchestration/services/{name}      # Update service
DELETE /orchestration/services/{name}      # Delete service
POST   /orchestration/services/{name}/scale # Scale service
POST   /orchestration/services/{name}/update # Update service
POST   /orchestration/services/{name}/rollback # Rollback service
GET    /orchestration/containers           # List all containers
GET    /orchestration/containers/{id}      # Get container details
POST   /orchestration/containers/{id}/stop  # Stop container
POST   /orchestration/containers/{id}/start # Start container
DELETE /orchestration/containers/{id}      # Remove container
GET    /orchestration/nodes                # List cluster nodes
GET    /orchestration/nodes/{id}           # Get node details
GET    /orchestration/networks             # List networks
GET    /orchestration/volumes              # List volumes
GET    /orchestration/status               # Get cluster status
GET    /orchestration/metrics              # Get cluster metrics
```

## Service Status Response
```json
{
  "services": [
    {
      "name": "web",
      "image": "nginx:latest",
      "desired_replicas": 3,
      "running_replicas": 3,
      "healthy_replicas": 3,
      "unhealthy_replicas": 0,
      "status": "healthy",
      "ports": [
        {"host": 80, "container": 80},
        {"host": 443, "container": 443}
      ],
      "resources": {
        "cpus": "0.5",
        "memory": "512M"
      },
      "created_at": "2023-01-01T10:00:00Z",
      "updated_at": "2023-01-01T10:00:00Z"
    },
    {
      "name": "api",
      "image": "myapp/api:latest",
      "desired_replicas": 2,
      "running_replicas": 2,
      "healthy_replicas": 1,
      "unhealthy_replicas": 1,
      "status": "degraded",
      "ports": [
        {"host": 8080, "container": 8080}
      ],
      "resources": {
        "cpus": "1.0",
        "memory": "1G"
      },
      "created_at": "2023-01-01T09:30:00Z",
      "updated_at": "2023-01-01T09:30:00Z"
    }
  ],
  "cluster": {
    "name": "production-cluster",
    "nodes": {
      "total": 4,
      "managers": 2,
      "workers": 2,
      "healthy": 4,
      "unhealthy": 0
    },
    "resources": {
      "total_cpus": 8,
      "used_cpus": 3.5,
      "total_memory": "16G",
      "used_memory": "4.5G"
    }
  },
  "timestamp": "2023-01-01T10:00:00Z"
}
```

## Container Metrics Response
```json
{
  "containers": [
    {
      "id": "container_5f4d4a4b4c4d4e4f50515253",
      "name": "web-1",
      "service": "web",
      "image": "nginx:latest",
      "status": "running",
      "health": "healthy",
      "ports": [
        {"host": 80, "container": 80},
        {"host": 443, "container": 443}
      ],
      "resources": {
        "cpus": "0.25",
        "memory": "128M",
        "memory_limit": "512M"
      },
      "network": {
        "rx_bytes": 1024000,
        "tx_bytes": 512000
      },
      "created_at": "2023-01-01T10:00:00Z",
      "started_at": "2023-01-01T10:00:05Z"
    }
  ]
}
```

## Evaluation Criteria
- [ ] Container deployment and provisioning work
- [ ] Container lifecycle management functions
- [ ] Image management and versioning operate
- [ ] Resource allocation and constraints enforced
- [ ] Service definition and deployment successful
- [ ] Service discovery and registration work
- [ ] Load balancing and routing function
- [ ] Service scaling and auto-scaling operate
- [ ] Container networking configuration works
- [ ] Volume management and persistence function
- [ ] Health checks and monitoring operate
- [ ] Cluster management capabilities work
- [ ] Security and access control implemented
- [ ] Code is well-organized and documented
- [ ] Tests cover orchestration functionality

## Resources
- [Docker Swarm](https://docs.docker.com/engine/swarm/)
- [Kubernetes](https://kubernetes.io/)
- [Docker Compose](https://docs.docker.com/compose/)
- [Container Orchestration](https://cloud.google.com/kubernetes-engine/kubernetes-comic)