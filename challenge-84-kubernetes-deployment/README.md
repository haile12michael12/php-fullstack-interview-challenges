# Challenge 84: Kubernetes Deployment

## Description
In this challenge, you'll implement Kubernetes deployment strategies to manage containerized applications in a production-grade orchestration environment. Kubernetes deployment is essential for scalable, resilient, and manageable application deployments.

## Learning Objectives
- Understand Kubernetes deployment concepts and resources
- Implement deployment strategies and rollouts
- Create service discovery and load balancing
- Handle auto-scaling and resource management
- Manage configuration and secrets
- Implement monitoring and logging

## Requirements
Create a Kubernetes deployment system with the following features:

1. **Deployment Management**:
   - Deployment creation and configuration
   - Rolling updates and rollbacks
   - Blue-green deployments
   - Canary deployments
   - Deployment strategies
   - Resource management

2. **Service Management**:
   - Service creation and configuration
   - Service discovery
   - Load balancing
   - Ingress controllers
   - Network policies
   - External services

3. **Configuration Management**:
   - ConfigMap management
   - Secret management
   - Environment variables
   - Volume mounting
   - Configuration updates
   - Secure configuration

4. **Advanced Features**:
   - Auto-scaling (HPA)
   - Resource quotas and limits
   - Pod disruption budgets
   - Health checks and probes
   - Monitoring and metrics
   - Logging integration

## Features to Implement
- [ ] Deployment creation and configuration
- [ ] Rolling updates and rollbacks
- [ ] Blue-green deployments
- [ ] Canary deployments
- [ ] Service creation and configuration
- [ ] Service discovery and load balancing
- [ ] Ingress controllers
- [ ] ConfigMap and Secret management
- [ ] Auto-scaling (HPA)
- [ ] Resource quotas and limits
- [ ] Health checks and probes
- [ ] Monitoring and metrics

## Project Structure
```
backend-php/
├── src/
│   ├── Kubernetes/
│   │   ├── DeploymentManager.php
│   │   ├── ServiceManager.php
│   │   ├── ConfigManager.php
│   │   ├── ScalingManager.php
│   │   ├── Strategy/
│   │   │   ├── RollingUpdate.php
│   │   │   ├── BlueGreen.php
│   │   │   ├── Canary.php
│   │   │   └── Recreate.php
│   │   ├── Resources/
│   │   │   ├── Deployment.php
│   │   │   ├── Service.php
│   │   │   ├── ConfigMap.php
│   │   │   ├── Secret.php
│   │   │   └── Ingress.php
│   │   └── Client/
│   │       ├── KubernetesClient.php
│   │       ├── ApiClient.php
│   │       └── Watcher.php
│   └── Services/
├── public/
│   └── index.php
├── kubernetes/
│   ├── deployments/
│   │   ├── web-deployment.yaml
│   │   ├── api-deployment.yaml
│   │   └── database-deployment.yaml
│   ├── services/
│   │   ├── web-service.yaml
│   │   ├── api-service.yaml
│   │   └── database-service.yaml
│   ├── config/
│   │   ├── app-config.yaml
│   │   └── database-secrets.yaml
│   ├── ingress/
│   │   └── app-ingress.yaml
│   ├── hpa/
│   │   └── api-hpa.yaml
│   └── scripts/
├── storage/
│   └── kubernetes/
├── config/
│   └── kubernetes.php
└── vendor/

frontend-react/
├── src/
│   ├── api/
│   │   └── kubernetes.js
│   ├── components/
│   │   ├── Dashboard/
│   │   │   ├── ClusterStatus.jsx
│   │   │   ├── DeploymentList.jsx
│   │   │   └── PodMetrics.jsx
│   │   ├── Deployments/
│   │   │   ├── DeploymentDetail.jsx
│   │   │   └── DeploymentCreate.jsx
│   │   └── Services/
│   │       ├── ServiceList.jsx
│   │       └── ServiceDetail.jsx
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

## Deployment Management

### Deployment Definition
Define Kubernetes deployment specifications:
```php
class Deployment
{
    private $name;
    private $namespace;
    private $replicas;
    private $selector;
    private $template;
    private $strategy;
    
    public function __construct($config)
    {
        $this->name = $config['name'];
        $this->namespace = $config['namespace'] ?? 'default';
        $this->replicas = $config['replicas'] ?? 1;
        $this->selector = $config['selector'] ?? [];
        $this->template = $config['template'] ?? [];
        $this->strategy = $config['strategy'] ?? ['type' => 'RollingUpdate'];
    }
    
    public function toKubernetesSpec()
    {
        return [
            'apiVersion' => 'apps/v1',
            'kind' => 'Deployment',
            'metadata' => [
                'name' => $this->name,
                'namespace' => $this->namespace
            ],
            'spec' => [
                'replicas' => $this->replicas,
                'selector' => $this->selector,
                'template' => $this->template,
                'strategy' => $this->strategy
            ]
        ];
    }
    
    public function setReplicas($replicas)
    {
        $this->replicas = $replicas;
        return $this;
    }
    
    public function setStrategy($strategy)
    {
        $this->strategy = $strategy;
        return $this;
    }
}
```

### Deployment Manager
Manage deployment lifecycle:
```php
class DeploymentManager
{
    private $kubernetesClient;
    private $strategyManager;
    
    public function createDeployment(Deployment $deployment)
    {
        $spec = $deployment->toKubernetesSpec();
        return $this->kubernetesClient->createDeployment($spec);
    }
    
    public function updateDeployment($name, Deployment $newDeployment)
    {
        $spec = $newDeployment->toKubernetesSpec();
        return $this->kubernetesClient->updateDeployment($name, $spec);
    }
    
    public function deleteDeployment($name, $namespace = 'default')
    {
        return $this->kubernetesClient->deleteDeployment($name, $namespace);
    }
    
    public function rolloutDeployment($name, $image, $namespace = 'default')
    {
        // Get current deployment
        $deployment = $this->kubernetesClient->getDeployment($name, $namespace);
        
        // Update container image
        $deployment['spec']['template']['spec']['containers'][0]['image'] = $image;
        
        // Apply the update
        return $this->kubernetesClient->updateDeployment($name, $deployment);
    }
    
    public function rollbackDeployment($name, $revision = null, $namespace = 'default')
    {
        if ($revision) {
            return $this->kubernetesClient->rollbackDeployment($name, $revision, $namespace);
        } else {
            // Rollback to previous revision
            return $this->kubernetesClient->rollbackDeployment($name, 'previous', $namespace);
        }
    }
    
    public function scaleDeployment($name, $replicas, $namespace = 'default')
    {
        return $this->kubernetesClient->scaleDeployment($name, $replicas, $namespace);
    }
    
    public function getDeploymentStatus($name, $namespace = 'default')
    {
        $deployment = $this->kubernetesClient->getDeployment($name, $namespace);
        $pods = $this->kubernetesClient->getPodsByDeployment($name, $namespace);
        
        $readyPods = 0;
        $totalPods = count($pods);
        
        foreach ($pods as $pod) {
            if ($pod['status']['phase'] === 'Running' && $this->isPodReady($pod)) {
                $readyPods++;
            }
        }
        
        return [
            'name' => $name,
            'namespace' => $namespace,
            'desired_replicas' => $deployment['spec']['replicas'],
            'current_replicas' => $totalPods,
            'ready_replicas' => $readyPods,
            'updated_replicas' => $deployment['status']['updatedReplicas'] ?? 0,
            'available_replicas' => $deployment['status']['availableReplicas'] ?? 0,
            'status' => $readyPods === $deployment['spec']['replicas'] ? 'healthy' : 'degraded'
        ];
    }
    
    private function isPodReady($pod)
    {
        foreach ($pod['status']['conditions'] as $condition) {
            if ($condition['type'] === 'Ready' && $condition['status'] === 'True') {
                return true;
            }
        }
        return false;
    }
}
```

## Deployment Strategies

### Rolling Update Strategy
Gradually replace pods with new versions:
```php
class RollingUpdateStrategy
{
    public function deploy($deployment, $newImage)
    {
        // Set rolling update strategy parameters
        $deployment->setStrategy([
            'type' => 'RollingUpdate',
            'rollingUpdate' => [
                'maxUnavailable' => 1,
                'maxSurge' => 1
            ]
        ]);
        
        // Update the deployment with new image
        return $this->deploymentManager->rolloutDeployment(
            $deployment->getName(),
            $newImage
        );
    }
}
```

### Blue-Green Deployment Strategy
Deploy new version alongside existing version:
```php
class BlueGreenStrategy
{
    public function deploy($serviceName, $newImage)
    {
        // Create green deployment (new version)
        $greenDeployment = $this->createGreenDeployment($serviceName, $newImage);
        $this->deploymentManager->createDeployment($greenDeployment);
        
        // Wait for green deployment to be ready
        $this->waitForDeploymentReady($greenDeployment->getName());
        
        // Update service to point to green deployment
        $this->serviceManager->updateServiceSelector($serviceName, [
            'app' => $serviceName,
            'version' => 'green'
        ]);
        
        // Optionally delete blue deployment after verification
        // $this->deploymentManager->deleteDeployment($blueDeployment->getName());
    }
    
    private function createGreenDeployment($serviceName, $newImage)
    {
        return new Deployment([
            'name' => $serviceName . '-green',
            'replicas' => 3,
            'selector' => [
                'matchLabels' => [
                    'app' => $serviceName,
                    'version' => 'green'
                ]
            ],
            'template' => [
                'metadata' => [
                    'labels' => [
                        'app' => $serviceName,
                        'version' => 'green'
                    ]
                ],
                'spec' => [
                    'containers' => [
                        [
                            'name' => $serviceName,
                            'image' => $newImage,
                            'ports' => [
                                ['containerPort' => 8080]
                            ]
                        ]
                    ]
                ]
            ]
        ]);
    }
}
```

## Configuration Example
```php
[
    'cluster' => [
        'context' => 'production',
        'namespace' => 'default',
        'api_server' => 'https://kubernetes.default.svc',
        'token_file' => '/var/run/secrets/kubernetes.io/serviceaccount/token',
        'ca_file' => '/var/run/secrets/kubernetes.io/serviceaccount/ca.crt'
    ],
    'deployments' => [
        'web' => [
            'replicas' => 3,
            'image' => 'nginx:latest',
            'ports' => [
                ['containerPort' => 80]
            ],
            'resources' => [
                'requests' => [
                    'cpu' => '100m',
                    'memory' => '128Mi'
                ],
                'limits' => [
                    'cpu' => '200m',
                    'memory' => '256Mi'
                ]
            ],
            'liveness_probe' => [
                'httpGet' => [
                    'path' => '/',
                    'port' => 80
                ],
                'initialDelaySeconds' => 30,
                'periodSeconds' => 10
            ],
            'readiness_probe' => [
                'httpGet' => [
                    'path' => '/',
                    'port' => 80
                ],
                'initialDelaySeconds' => 5,
                'periodSeconds' => 5
            ]
        ],
        'api' => [
            'replicas' => 2,
            'image' => 'myapp/api:latest',
            'ports' => [
                ['containerPort' => 8080]
            ],
            'env_from' => [
                'configMapRef' => ['name' => 'api-config'],
                'secretRef' => ['name' => 'api-secrets']
            ],
            'volume_mounts' => [
                [
                    'name' => 'config-volume',
                    'mountPath' => '/etc/config'
                ]
            ],
            'volumes' => [
                [
                    'name' => 'config-volume',
                    'configMap' => ['name' => 'api-config']
                ]
            ],
            'resources' => [
                'requests' => [
                    'cpu' => '200m',
                    'memory' => '256Mi'
                ],
                'limits' => [
                    'cpu' => '500m',
                    'memory' => '512Mi'
                ]
            ]
        ]
    ],
    'services' => [
        'web' => [
            'type' => 'LoadBalancer',
            'ports' => [
                [
                    'port' => 80,
                    'targetPort' => 80,
                    'protocol' => 'TCP'
                ]
            ],
            'selector' => [
                'app' => 'web'
            ]
        ],
        'api' => [
            'type' => 'ClusterIP',
            'ports' => [
                [
                    'port' => 8080,
                    'targetPort' => 8080,
                    'protocol' => 'TCP'
                ]
            ],
            'selector' => [
                'app' => 'api'
            ]
        ]
    ],
    'ingress' => [
        'enabled' => true,
        'rules' => [
            [
                'host' => 'myapp.example.com',
                'paths' => [
                    [
                        'path' => '/',
                        'backend' => [
                            'serviceName' => 'web',
                            'servicePort' => 80
                        ]
                    ],
                    [
                        'path' => '/api',
                        'backend' => [
                            'serviceName' => 'api',
                            'servicePort' => 8080
                        ]
                    ]
                ]
            ]
        ]
    ],
    'scaling' => [
        'hpa' => [
            'enabled' => true,
            'min_replicas' => 1,
            'max_replicas' => 10,
            'metrics' => [
                [
                    'type' => 'Resource',
                    'resource' => [
                        'name' => 'cpu',
                        'target' => [
                            'type' => 'Utilization',
                            'averageUtilization' => 70
                        ]
                    ]
                ]
            ]
        ]
    ]
]
```

## Auto Scaling Implementation
```php
class ScalingManager
{
    private $kubernetesClient;
    
    public function createHorizontalPodAutoscaler($deploymentName, $config)
    {
        $hpaSpec = [
            'apiVersion' => 'autoscaling/v2',
            'kind' => 'HorizontalPodAutoscaler',
            'metadata' => [
                'name' => $deploymentName . '-hpa',
                'namespace' => $config['namespace'] ?? 'default'
            ],
            'spec' => [
                'scaleTargetRef' => [
                    'apiVersion' => 'apps/v1',
                    'kind' => 'Deployment',
                    'name' => $deploymentName
                ],
                'minReplicas' => $config['min_replicas'] ?? 1,
                'maxReplicas' => $config['max_replicas'] ?? 10,
                'metrics' => $config['metrics'] ?? []
            ]
        ];
        
        return $this->kubernetesClient->createResource('HorizontalPodAutoscaler', $hpaSpec);
    }
    
    public function getScalingMetrics($deploymentName, $namespace = 'default')
    {
        $hpa = $this->kubernetesClient->getResource(
            'HorizontalPodAutoscaler', 
            $deploymentName . '-hpa', 
            $namespace
        );
        
        $metrics = $this->kubernetesClient->getMetrics($deploymentName, $namespace);
        
        return [
            'hpa' => $hpa,
            'current_metrics' => $metrics,
            'recommended_replicas' => $hpa['status']['desiredReplicas'] ?? 1,
            'current_replicas' => $hpa['status']['currentReplicas'] ?? 1
        ];
    }
}
```

## API Endpoints
```
# Kubernetes Deployment Management
POST   /kubernetes/deployments              # Create new deployment
GET    /kubernetes/deployments             # List all deployments
GET    /kubernetes/deployments/{name}      # Get deployment details
PUT    /kubernetes/deployments/{name}      # Update deployment
DELETE /kubernetes/deployments/{name}      # Delete deployment
POST   /kubernetes/deployments/{name}/scale # Scale deployment
POST   /kubernetes/deployments/{name}/rollout # Rollout new version
POST   /kubernetes/deployments/{name}/rollback # Rollback deployment
GET    /kubernetes/pods                    # List all pods
GET    /kubernetes/pods/{name}             # Get pod details
DELETE /kubernetes/pods/{name}             # Delete pod
GET    /kubernetes/services                # List all services
POST   /kubernetes/services                # Create new service
GET    /kubernetes/services/{name}         # Get service details
PUT    /kubernetes/services/{name}         # Update service
DELETE /kubernetes/services/{name}         # Delete service
GET    /kubernetes/configmaps              # List all configmaps
POST   /kubernetes/configmaps              # Create new configmap
GET    /kubernetes/secrets                 # List all secrets
POST   /kubernetes/secrets                 # Create new secret
GET    /kubernetes/status                  # Get cluster status
GET    /kubernetes/metrics                 # Get cluster metrics
GET    /kubernetes/events                  # Get cluster events
```

## Deployment Status Response
```json
{
  "deployments": [
    {
      "name": "web",
      "namespace": "default",
      "desired_replicas": 3,
      "current_replicas": 3,
      "ready_replicas": 3,
      "updated_replicas": 3,
      "available_replicas": 3,
      "status": "healthy",
      "image": "nginx:latest",
      "created_at": "2023-01-01T10:00:00Z",
      "updated_at": "2023-01-01T10:00:00Z"
    },
    {
      "name": "api",
      "namespace": "default",
      "desired_replicas": 2,
      "current_replicas": 2,
      "ready_replicas": 1,
      "updated_replicas": 2,
      "available_replicas": 1,
      "status": "degraded",
      "image": "myapp/api:latest",
      "created_at": "2023-01-01T09:30:00Z",
      "updated_at": "2023-01-01T10:00:00Z"
    }
  ],
  "cluster": {
    "nodes": {
      "total": 3,
      "ready": 3,
      "not_ready": 0
    },
    "resources": {
      "total_cpu": "6",
      "used_cpu": "1.5",
      "total_memory": "12Gi",
      "used_memory": "2.1Gi"
    }
  },
  "timestamp": "2023-01-01T10:00:00Z"
}
```

## Pod Metrics Response
```json
{
  "pods": [
    {
      "name": "web-7d5b8c9c4-xl2v9",
      "namespace": "default",
      "deployment": "web",
      "status": "Running",
      "ready": true,
      "restarts": 0,
      "node": "node-1",
      "ip": "10.244.1.5",
      "resources": {
        "cpu": "15m",
        "memory": "24Mi",
        "cpu_limit": "200m",
        "memory_limit": "256Mi"
      },
      "created_at": "2023-01-01T10:00:05Z",
      "started_at": "2023-01-01T10:00:10Z"
    }
  ]
}
```

## Evaluation Criteria
- [ ] Deployment creation and configuration work
- [ ] Rolling updates and rollbacks function
- [ ] Blue-green deployments operate correctly
- [ ] Canary deployments work as expected
- [ ] Service creation and configuration successful
- [ ] Service discovery and load balancing function
- [ ] Ingress controllers operate correctly
- [ ] ConfigMap and Secret management work
- [ ] Auto-scaling (HPA) functions properly
- [ ] Resource quotas and limits enforced
- [ ] Health checks and probes work
- [ ] Monitoring and metrics collection operate
- [ ] Code is well-organized and documented
- [ ] Tests cover Kubernetes deployment functionality

## Resources
- [Kubernetes Documentation](https://kubernetes.io/docs/)
- [Kubernetes Deployments](https://kubernetes.io/docs/concepts/workloads/controllers/deployment/)
- [Kubernetes Services](https://kubernetes.io/docs/concepts/services-networking/service/)
- [Kubernetes ConfigMaps and Secrets](https://kubernetes.io/docs/concepts/configuration/)