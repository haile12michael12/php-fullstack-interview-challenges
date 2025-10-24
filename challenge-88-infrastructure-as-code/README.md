# Challenge 88: Infrastructure as Code

## Description
In this challenge, you'll implement an Infrastructure as Code (IaC) system to manage and provision computing infrastructure through machine-readable definition files. IaC is essential for automating infrastructure provisioning, ensuring consistency, and enabling version-controlled infrastructure management.

## Learning Objectives
- Understand Infrastructure as Code concepts and benefits
- Implement infrastructure provisioning and management
- Create declarative infrastructure definitions
- Handle infrastructure state management
- Manage infrastructure lifecycle operations
- Implement infrastructure testing and validation

## Requirements
Create an Infrastructure as Code system with the following features:

1. **Infrastructure Provisioning**:
   - Resource definition and configuration
   - Declarative infrastructure specifications
   - Infrastructure state management
   - Resource dependency management
   - Infrastructure lifecycle operations
   - Multi-cloud support

2. **State Management**:
   - State storage and retrieval
   - State locking and concurrency
   - State versioning and history
   - Remote state backends
   - State migration
   - State validation

3. **Resource Management**:
   - Resource creation and deletion
   - Resource updating and modification
   - Resource import and export
   - Resource tagging and metadata
   - Resource grouping and modules
   - Resource validation

4. **Advanced Features**:
   - Infrastructure testing and validation
   - Infrastructure drift detection
   - Infrastructure cost estimation
   - Infrastructure documentation
   - Infrastructure security scanning
   - Infrastructure compliance checking

## Features to Implement
- [ ] Resource definition and configuration
- [ ] Declarative infrastructure specifications
- [ ] Infrastructure state management
- [ ] Resource dependency management
- [ ] State storage and retrieval
- [ ] State locking and concurrency
- [ ] Resource creation and deletion
- [ ] Resource updating and modification
- [ ] Infrastructure testing and validation
- [ ] Infrastructure drift detection
- [ ] Infrastructure cost estimation
- [ ] Infrastructure security scanning

## Project Structure
```
backend-php/
├── src/
│   ├── Iac/
│   │   ├── InfrastructureManager.php
│   │   ├── Resource/
│   │   │   ├── ResourceManager.php
│   │   │   ├── ResourceDefinition.php
│   │   │   ├── ResourceProvider.php
│   │   │   └── ResourceValidator.php
│   │   ├── State/
│   │   │   ├── StateManager.php
│   │   │   ├── StateBackend.php
│   │   │   ├── StateLock.php
│   │   │   └── StateHistory.php
│   │   ├── Provisioning/
│   │   │   ├── Provisioner.php
│   │   │   ├── LifecycleManager.php
│   │   │   └── DependencyResolver.php
│   │   └── Testing/
│   │       ├── InfrastructureTester.php
│   │       ├── DriftDetector.php
│   │       ├── CostEstimator.php
│   │       └── SecurityScanner.php
│   └── Services/
├── public/
│   └── index.php
├── infrastructure/
│   ├── definitions/
│   │   ├── main.tf
│   │   ├── variables.tf
│   │   └── outputs.tf
│   ├── modules/
│   │   ├── web-server/
│   │   ├── database/
│   │   └── network/
│   ├── state/
│   │   └── terraform.tfstate
│   ├── tests/
│   │   └── infrastructure-tests/
│   └── scripts/
├── storage/
│   └── iac/
├── config/
│   └── iac.php
└── vendor/

frontend-react/
├── src/
│   ├── api/
│   │   └── iac.js
│   ├── components/
│   │   ├── Dashboard/
│   │   │   ├── InfrastructureOverview.jsx
│   │   │   ├── ResourceList.jsx
│   │   │   └── StateStatus.jsx
│   │   ├── Definitions/
│   │   │   ├── DefinitionList.jsx
│   │   │   ├── DefinitionEditor.jsx
│   │   │   └── DefinitionValidator.jsx
│   │   ├── State/
│   │   │   ├── StateViewer.jsx
│   │   │   └── StateHistory.jsx
│   │   └── Testing/
│   │       ├── TestResults.jsx
│   │       └── DriftReport.jsx
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

## Infrastructure Management

### Resource Definition
Define infrastructure resources declaratively:
```php
class ResourceDefinition
{
    private $type;
    private $name;
    private $properties;
    private $dependencies;
    private $provider;
    
    public function __construct($config)
    {
        $this->type = $config['type'];
        $this->name = $config['name'];
        $this->properties = $config['properties'] ?? [];
        $this->dependencies = $config['dependencies'] ?? [];
        $this->provider = $config['provider'] ?? 'aws';
    }
    
    public function getType()
    {
        return $this->type;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getProperties()
    {
        return $this->properties;
    }
    
    public function getDependencies()
    {
        return $this->dependencies;
    }
    
    public function getProvider()
    {
        return $this->provider;
    }
    
    public function validate()
    {
        // Validate required properties
        $requiredProperties = $this->getRequiredProperties();
        foreach ($requiredProperties as $property) {
            if (!isset($this->properties[$property])) {
                throw new InvalidResourceException("Missing required property: $property");
            }
        }
        
        // Validate property types
        $this->validatePropertyTypes();
        
        return true;
    }
    
    private function getRequiredProperties()
    {
        $required = [
            'aws_instance' => ['ami', 'instance_type'],
            'aws_s3_bucket' => ['bucket'],
            'aws_db_instance' => ['engine', 'instance_class', 'allocated_storage'],
            'google_compute_instance' => ['machine_type', 'zone'],
            'azure_virtual_machine' => ['vm_size', 'location']
        ];
        
        return $required[$this->type] ?? [];
    }
    
    private function validatePropertyTypes()
    {
        $typeValidators = [
            'aws_instance' => [
                'instance_type' => 'string',
                'ami' => 'string',
                'count' => 'integer'
            ],
            'aws_s3_bucket' => [
                'bucket' => 'string',
                'acl' => 'string'
            ]
        ];
        
        $validators = $typeValidators[$this->type] ?? [];
        foreach ($validators as $property => $expectedType) {
            if (isset($this->properties[$property])) {
                $actualType = gettype($this->properties[$property]);
                if ($actualType !== $expectedType) {
                    throw new InvalidResourceException(
                        "Property $property expected to be $expectedType, got $actualType"
                    );
                }
            }
        }
    }
}
```

### Infrastructure Manager
Manage infrastructure provisioning and lifecycle:
```php
class InfrastructureManager
{
    private $resourceManager;
    private $stateManager;
    private $provisioner;
    private $dependencyResolver;
    
    public function applyInfrastructure($definitionFile)
    {
        $startTime = time();
        $executionId = uniqid('iac_');
        
        try {
            // 1. Parse infrastructure definition
            $definitions = $this->parseDefinitions($definitionFile);
            
            // 2. Validate resource definitions
            $this->validateDefinitions($definitions);
            
            // 3. Resolve dependencies
            $executionPlan = $this->dependencyResolver->resolve($definitions);
            
            // 4. Get current state
            $currentState = $this->stateManager->getCurrentState();
            
            // 5. Calculate changes
            $changes = $this->calculateChanges($definitions, $currentState);
            
            // 6. Acquire state lock
            $lock = $this->stateManager->acquireLock($executionId);
            
            try {
                // 7. Apply changes
                $results = $this->provisioner->applyChanges($changes, $executionPlan);
                
                // 8. Update state
                $this->stateManager->updateState($definitions, $results);
                
                // 9. Release lock
                $this->stateManager->releaseLock($lock);
                
                $endTime = time();
                
                return [
                    'execution_id' => $executionId,
                    'status' => 'success',
                    'duration' => $endTime - $startTime,
                    'changes' => $results,
                    'resources_created' => count(array_filter($results, function($r) { return $r['action'] === 'create'; })),
                    'resources_updated' => count(array_filter($results, function($r) { return $r['action'] === 'update'; })),
                    'resources_deleted' => count(array_filter($results, function($r) { return $r['action'] === 'delete'; }))
                ];
            } catch (Exception $e) {
                // Release lock on error
                $this->stateManager->releaseLock($lock);
                throw $e;
            }
        } catch (Exception $e) {
            return [
                'execution_id' => $executionId,
                'status' => 'failed',
                'duration' => time() - $startTime,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ];
        }
    }
    
    public function destroyInfrastructure($definitionFile)
    {
        $definitions = $this->parseDefinitions($definitionFile);
        $currentState = $this->stateManager->getCurrentState();
        
        // Calculate resources to delete (reverse dependency order)
        $deletionPlan = $this->dependencyResolver->resolveForDeletion($definitions, $currentState);
        
        $lock = $this->stateManager->acquireLock('destroy_' . uniqid());
        
        try {
            $results = $this->provisioner->destroyResources($deletionPlan);
            $this->stateManager->clearState();
            $this->stateManager->releaseLock($lock);
            
            return [
                'status' => 'success',
                'resources_deleted' => count($results),
                'results' => $results
            ];
        } catch (Exception $e) {
            $this->stateManager->releaseLock($lock);
            throw $e;
        }
    }
    
    private function parseDefinitions($definitionFile)
    {
        $content = file_get_contents($definitionFile);
        $parsed = json_decode($content, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidDefinitionException('Invalid JSON in definition file');
        }
        
        $definitions = [];
        foreach ($parsed['resources'] as $resourceConfig) {
            $definitions[] = new ResourceDefinition($resourceConfig);
        }
        
        return $definitions;
    }
    
    private function validateDefinitions($definitions)
    {
        foreach ($definitions as $definition) {
            $definition->validate();
        }
    }
    
    private function calculateChanges($definitions, $currentState)
    {
        $changes = [];
        
        // Check for new resources
        foreach ($definitions as $definition) {
            $resourceId = $definition->getType() . '.' . $definition->getName();
            if (!isset($currentState[$resourceId])) {
                $changes[] = [
                    'action' => 'create',
                    'resource' => $definition
                ];
            }
        }
        
        // Check for updates
        foreach ($definitions as $definition) {
            $resourceId = $definition->getType() . '.' . $definition->getName();
            if (isset($currentState[$resourceId])) {
                if ($this->hasResourceChanged($definition, $currentState[$resourceId])) {
                    $changes[] = [
                        'action' => 'update',
                        'resource' => $definition,
                        'current_state' => $currentState[$resourceId]
                    ];
                }
            }
        }
        
        // Check for deletions
        foreach ($currentState as $resourceId => $state) {
            $found = false;
            foreach ($definitions as $definition) {
                if ($definition->getType() . '.' . $definition->getName() === $resourceId) {
                    $found = true;
                    break;
                }
            }
            
            if (!$found) {
                $changes[] = [
                    'action' => 'delete',
                    'resource_id' => $resourceId,
                    'current_state' => $state
                ];
            }
        }
        
        return $changes;
    }
}
```

## State Management

### State Manager
Manage infrastructure state and consistency:
```php
class StateManager
{
    private $backend;
    private $lockManager;
    private $historyManager;
    
    public function __construct($backendConfig)
    {
        $this->backend = new StateBackend($backendConfig);
        $this->lockManager = new StateLock();
        $this->historyManager = new StateHistory();
    }
    
    public function getCurrentState()
    {
        return $this->backend->getState();
    }
    
    public function updateState($definitions, $results)
    {
        $newState = [];
        
        // Build new state from definitions and results
        foreach ($definitions as $definition) {
            $resourceId = $definition->getType() . '.' . $definition->getName();
            $newState[$resourceId] = [
                'type' => $definition->getType(),
                'name' => $definition->getName(),
                'properties' => $definition->getProperties(),
                'provider' => $definition->getProvider(),
                'created_at' => time(),
                'updated_at' => time()
            ];
        }
        
        // Update state with actual resource IDs from results
        foreach ($results as $result) {
            if (isset($result['resource_id'])) {
                $resourceId = $result['resource']['type'] . '.' . $result['resource']['name'];
                if (isset($newState[$resourceId])) {
                    $newState[$resourceId]['actual_id'] = $result['resource_id'];
                    $newState[$resourceId]['outputs'] = $result['outputs'] ?? [];
                }
            }
        }
        
        // Save new state
        $this->backend->saveState($newState);
        
        // Record in history
        $this->historyManager->recordStateChange($newState);
    }
    
    public function acquireLock($executionId)
    {
        $lock = $this->lockManager->acquire($executionId);
        if (!$lock) {
            throw new StateLockedException('Infrastructure state is locked by another operation');
        }
        return $lock;
    }
    
    public function releaseLock($lock)
    {
        $this->lockManager->release($lock);
    }
    
    public function getStateHistory($limit = 10)
    {
        return $this->historyManager->getHistory($limit);
    }
    
    public function rollbackToState($stateVersion)
    {
        $state = $this->historyManager->getStateByVersion($stateVersion);
        $this->backend->saveState($state);
        return $state;
    }
}
```

## Configuration Example
```php
[
    'providers' => [
        'aws' => [
            'enabled' => true,
            'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
            'credentials' => [
                'access_key' => env('AWS_ACCESS_KEY_ID'),
                'secret_key' => env('AWS_SECRET_ACCESS_KEY')
            ]
        ],
        'google' => [
            'enabled' => true,
            'project' => env('GOOGLE_PROJECT_ID'),
            'credentials_file' => env('GOOGLE_CREDENTIALS_FILE')
        ],
        'azure' => [
            'enabled' => true,
            'subscription_id' => env('AZURE_SUBSCRIPTION_ID'),
            'tenant_id' => env('AZURE_TENANT_ID'),
            'client_id' => env('AZURE_CLIENT_ID'),
            'client_secret' => env('AZURE_CLIENT_SECRET')
        ]
    ],
    'state' => [
        'backend' => 's3',
        's3' => [
            'bucket' => env('IAC_STATE_BUCKET'),
            'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
            'key' => 'terraform.tfstate',
            'credentials' => [
                'access_key' => env('AWS_ACCESS_KEY_ID'),
                'secret_key' => env('AWS_SECRET_ACCESS_KEY')
            ]
        ],
        'locking' => [
            'enabled' => true,
            'timeout' => 300, // 5 minutes
            'retry_interval' => 5
        ],
        'history' => [
            'enabled' => true,
            'retention' => 86400 * 30 // 30 days
        ]
    ],
    'provisioning' => [
        'parallelism' => 10,
        'timeout' => 3600, // 1 hour
        'retry_attempts' => 3,
        'retry_delay' => 30
    ],
    'validation' => [
        'enabled' => true,
        'strict_mode' => true,
        'custom_validators' => [
            'aws_instance' => 'App\\Validators\\AwsInstanceValidator',
            'aws_s3_bucket' => 'App\\Validators\\S3BucketValidator'
        ]
    ],
    'testing' => [
        'enabled' => true,
        'drift_detection' => [
            'enabled' => true,
            'interval' => 3600, // 1 hour
            'notification_channels' => ['slack', 'email']
        ],
        'cost_estimation' => [
            'enabled' => true,
            'providers' => ['aws', 'google', 'azure']
        ],
        'security_scanning' => [
            'enabled' => true,
            'rules' => [
                'no_public_buckets' => true,
                'no_unencrypted_resources' => true,
                'no_overly_permissive_policies' => true
            ]
        ]
    ],
    'modules' => [
        'path' => '/infrastructure/modules',
        'registry' => [
            'enabled' => true,
            'url' => 'https://registry.example.com',
            'authentication' => [
                'token' => env('MODULE_REGISTRY_TOKEN')
            ]
        ]
    ]
]
```

## Infrastructure Definition Example
```json
{
  "terraform": {
    "required_version": ">= 1.0"
  },
  "provider": {
    "aws": {
      "region": "us-west-2"
    }
  },
  "variables": {
    "project_name": {
      "description": "Name of the project",
      "type": "string",
      "default": "my-app"
    },
    "environment": {
      "description": "Environment name",
      "type": "string",
      "default": "development"
    }
  },
  "resources": [
    {
      "type": "aws_vpc",
      "name": "main",
      "properties": {
        "cidr_block": "10.0.0.0/16",
        "enable_dns_hostnames": true,
        "enable_dns_support": true,
        "tags": {
          "Name": "${var.project_name}-${var.environment}-vpc"
        }
      }
    },
    {
      "type": "aws_subnet",
      "name": "public",
      "properties": {
        "vpc_id": "${aws_vpc.main.id}",
        "cidr_block": "10.0.1.0/24",
        "availability_zone": "us-west-2a",
        "map_public_ip_on_launch": true,
        "tags": {
          "Name": "${var.project_name}-${var.environment}-public-subnet"
        }
      },
      "dependencies": ["aws_vpc.main"]
    },
    {
      "type": "aws_instance",
      "name": "web",
      "properties": {
        "ami": "ami-0c55b159cbfafe1d0",
        "instance_type": "t3.micro",
        "subnet_id": "${aws_subnet.public.id}",
        "vpc_security_group_ids": ["${aws_security_group.web.id}"],
        "tags": {
          "Name": "${var.project_name}-${var.environment}-web-server"
        }
      },
      "dependencies": ["aws_subnet.public", "aws_security_group.web"]
    }
  ],
  "outputs": {
    "web_server_ip": {
      "value": "${aws_instance.web.public_ip}",
      "description": "Public IP address of the web server"
    },
    "vpc_id": {
      "value": "${aws_vpc.main.id}",
      "description": "ID of the main VPC"
    }
  }
}
```

## API Endpoints
```
# Infrastructure as Code Management
POST   /iac/apply                    # Apply infrastructure changes
POST   /iac/destroy                  # Destroy infrastructure
GET    /iac/state                    # Get current infrastructure state
GET    /iac/state/history            # Get state history
POST   /iac/state/rollback           # Rollback to previous state
GET    /iac/resources                # List infrastructure resources
GET    /iac/resources/{id}           # Get resource details
POST   /iac/resources/{id}/refresh    # Refresh resource state
GET    /iac/definitions              # List infrastructure definitions
POST   /iac/definitions              # Create infrastructure definition
GET    /iac/definitions/{name}       # Get definition details
PUT    /iac/definitions/{name}       # Update definition
DELETE /iac/definitions/{name}       # Delete definition
POST   /iac/validate                 # Validate infrastructure definition
GET    /iac/plan                     # Get infrastructure change plan
POST   /iac/import                   # Import existing resources
GET    /iac/modules                  # List available modules
POST   /iac/modules                  # Create module
GET    /iac/modules/{name}           # Get module details
PUT    /iac/modules/{name}           # Update module
DELETE /iac/modules/{name}           # Delete module
GET    /iac/testing/drift            # Check for infrastructure drift
GET    /iac/testing/cost             # Get cost estimation
GET    /iac/testing/security         # Get security scan results
POST   /iac/testing/run              # Run infrastructure tests
```

## Infrastructure State Response
```json
{
  "state": {
    "version": 4,
    "terraform_version": "1.0.0",
    "serial": 123,
    "lineage": "5f4d4a4b-4c4d-4e4f-5051-525354555657",
    "resources": [
      {
        "type": "aws_vpc",
        "name": "main",
        "provider": "aws",
        "actual_id": "vpc-12345678",
        "properties": {
          "cidr_block": "10.0.0.0/16",
          "enable_dns_hostnames": true,
          "enable_dns_support": true
        },
        "outputs": {
          "id": "vpc-12345678",
          "arn": "arn:aws:ec2:us-west-2:123456789012:vpc/vpc-12345678",
          "cidr_block": "10.0.0.0/16"
        },
        "created_at": "2023-01-01T10:00:00Z",
        "updated_at": "2023-01-01T10:00:00Z"
      },
      {
        "type": "aws_instance",
        "name": "web",
        "provider": "aws",
        "actual_id": "i-1234567890abcdef0",
        "properties": {
          "ami": "ami-0c55b159cbfafe1d0",
          "instance_type": "t3.micro"
        },
        "outputs": {
          "id": "i-1234567890abcdef0",
          "arn": "arn:aws:ec2:us-west-2:123456789012:instance/i-1234567890abcdef0",
          "public_ip": "203.0.113.1",
          "private_ip": "10.0.1.100"
        },
        "created_at": "2023-01-01T10:05:00Z",
        "updated_at": "2023-01-01T10:05:00Z"
      }
    ]
  },
  "timestamp": "2023-01-01T10:00:00Z"
}
```

## Infrastructure Change Plan Response
```json
{
  "plan": {
    "execution_id": "plan_5f4d4a4b4c4d4e4f50515253",
    "changes": {
      "create": [
        {
          "type": "aws_security_group",
          "name": "web",
          "properties": {
            "name": "my-app-development-web-sg",
            "vpc_id": "vpc-12345678",
            "ingress": [
              {
                "from_port": 80,
                "to_port": 80,
                "protocol": "tcp",
                "cidr_blocks": ["0.0.0.0/0"]
              }
            ]
          }
        }
      ],
      "update": [
        {
          "type": "aws_instance",
          "name": "web",
          "current": {
            "instance_type": "t2.micro"
          },
          "planned": {
            "instance_type": "t3.micro"
          }
        }
      ],
      "delete": [
        {
          "type": "aws_s3_bucket",
          "name": "old-bucket",
          "actual_id": "my-old-bucket-12345"
        }
      ]
    },
    "summary": {
      "create": 1,
      "update": 1,
      "delete": 1
    }
  }
}
```

## Evaluation Criteria
- [ ] Resource definition and configuration work
- [ ] Declarative infrastructure specifications function
- [ ] Infrastructure state management operates correctly
- [ ] Resource dependency management works
- [ ] State storage and retrieval successful
- [ ] State locking and concurrency handled
- [ ] Resource creation and deletion function
- [ ] Resource updating and modification work
- [ ] Infrastructure testing and validation operate
- [ ] Infrastructure drift detection works
- [ ] Infrastructure cost estimation functions
- [ ] Infrastructure security scanning implemented
- [ ] Code is well-organized and documented
- [ ] Tests cover Infrastructure as Code functionality

## Resources
- [Infrastructure as Code](https://www.hashicorp.com/resources/what-is-infrastructure-as-code)
- [Terraform](https://www.terraform.io/)
- [AWS CloudFormation](https://aws.amazon.com/cloudformation/)
- [Azure Resource Manager](https://docs.microsoft.com/en-us/azure/azure-resource-manager/)
- [Google Cloud Deployment Manager](https://cloud.google.com/deployment-manager)