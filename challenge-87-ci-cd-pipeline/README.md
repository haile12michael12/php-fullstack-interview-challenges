# Challenge 87: CI/CD Pipeline

## Description
In this challenge, you'll implement a comprehensive CI/CD pipeline system to automate the build, test, and deployment processes for applications. CI/CD pipelines are essential for enabling rapid, reliable, and consistent software delivery.

## Learning Objectives
- Understand CI/CD pipeline concepts and best practices
- Implement continuous integration workflows
- Create continuous deployment strategies
- Handle pipeline orchestration and execution
- Manage pipeline security and access control
- Implement pipeline monitoring and observability

## Requirements
Create a CI/CD pipeline system with the following features:

1. **Pipeline Orchestration**:
   - Pipeline definition and configuration
   - Stage and step management
   - Parallel execution
   - Conditional execution
   - Pipeline triggers
   - Pipeline scheduling

2. **Continuous Integration**:
   - Source code integration
   - Automated building
   - Testing automation
   - Code quality checks
   - Security scanning
   - Artifact management

3. **Continuous Deployment**:
   - Deployment automation
   - Environment management
   - Rollback capabilities
   - Blue-green deployments
   - Canary deployments
   - Deployment validation

4. **Advanced Features**:
   - Pipeline templates and reusability
   - Pipeline visualization
   - Pipeline monitoring and alerts
   - Pipeline security and compliance
   - Integration with external tools
   - Pipeline analytics and reporting

## Features to Implement
- [ ] Pipeline definition and configuration
- [ ] Stage and step management
- [ ] Parallel execution
- [ ] Conditional execution
- [ ] Source code integration
- [ ] Automated building
- [ ] Testing automation
- [ ] Code quality checks
- [ ] Security scanning
- [ ] Deployment automation
- [ ] Environment management
- [ ] Rollback capabilities
- [ ] Pipeline templates and reusability
- [ ] Pipeline visualization
- [ ] Pipeline monitoring and alerts

## Project Structure
```
backend-php/
├── src/
│   ├── Pipeline/
│   │   ├── PipelineManager.php
│   │   ├── PipelineDefinition.php
│   │   ├── Stage.php
│   │   ├── Step.php
│   │   ├── Trigger.php
│   │   ├── Scheduler.php
│   │   └── Executor.php
│   ├── Ci/
│   │   ├── CiManager.php
│   │   ├── SourceIntegration.php
│   │   ├── Builder.php
│   │   ├── Tester.php
│   │   ├── CodeQualityChecker.php
│   │   └── ArtifactManager.php
│   ├── Cd/
│   │   ├── CdManager.php
│   │   ├── Deployer.php
│   │   ├── EnvironmentManager.php
│   │   ├── RollbackManager.php
│   │   └── DeploymentValidator.php
│   ├── Templates/
│   │   ├── PipelineTemplate.php
│   │   ├── TemplateManager.php
│   │   └── TemplateRegistry.php
│   └── Monitoring/
│       ├── PipelineMonitor.php
│       ├── AlertManager.php
│       └── Analytics.php
├── public/
│   └── index.php
├── pipelines/
│   ├── definitions/
│   │   ├── build-pipeline.yaml
│   │   ├── test-pipeline.yaml
│   │   └── deploy-pipeline.yaml
│   ├── templates/
│   │   ├── php-app-template.yaml
│   │   └── react-app-template.yaml
│   └── scripts/
├── storage/
│   └── pipelines/
├── config/
│   └── pipeline.php
└── vendor/

frontend-react/
├── src/
│   ├── api/
│   │   └── pipeline.js
│   ├── components/
│   │   ├── Dashboard/
│   │   │   ├── PipelineOverview.jsx
│   │   │   ├── PipelineStatus.jsx
│   │   │   └── ExecutionHistory.jsx
│   │   ├── Pipelines/
│   │   │   ├── PipelineList.jsx
│   │   │   ├── PipelineDetail.jsx
│   │   │   └── PipelineEditor.jsx
│   │   ├── Executions/
│   │   │   ├── ExecutionList.jsx
│   │   │   ├── ExecutionDetail.jsx
│   │   │   └── ExecutionLogs.jsx
│   │   └── Templates/
│   │       ├── TemplateList.jsx
│   │       └── TemplateEditor.jsx
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

## Pipeline Orchestration

### Pipeline Definition
Define pipeline structure and workflow:
```php
class PipelineDefinition
{
    private $name;
    private $stages;
    private $triggers;
    private $variables;
    private $notifications;
    
    public function __construct($config)
    {
        $this->name = $config['name'];
        $this->stages = $config['stages'] ?? [];
        $this->triggers = $config['triggers'] ?? [];
        $this->variables = $config['variables'] ?? [];
        $this->notifications = $config['notifications'] ?? [];
    }
    
    public function addStage($stage)
    {
        $this->stages[] = $stage;
    }
    
    public function getExecutionPlan()
    {
        $plan = [
            'pipeline' => $this->name,
            'stages' => []
        ];
        
        foreach ($this->stages as $stageConfig) {
            $stage = new Stage($stageConfig);
            $plan['stages'][] = $stage->getExecutionPlan();
        }
        
        return $plan;
    }
    
    public function validate()
    {
        // Validate pipeline structure
        if (empty($this->name)) {
            throw new InvalidPipelineException("Pipeline name is required");
        }
        
        if (empty($this->stages)) {
            throw new InvalidPipelineException("Pipeline must have at least one stage");
        }
        
        // Validate stages
        foreach ($this->stages as $stage) {
            $stageObj = new Stage($stage);
            $stageObj->validate();
        }
        
        return true;
    }
}
```

### Stage Management
Manage pipeline stages and execution:
```php
class Stage
{
    private $name;
    private $steps;
    private $conditions;
    private $parallel;
    private $environment;
    
    public function __construct($config)
    {
        $this->name = $config['name'];
        $this->steps = $config['steps'] ?? [];
        $this->conditions = $config['conditions'] ?? [];
        $this->parallel = $config['parallel'] ?? false;
        $this->environment = $config['environment'] ?? [];
    }
    
    public function execute($context)
    {
        $results = [];
        
        // Check conditions
        if (!$this->evaluateConditions($context)) {
            return ['status' => 'skipped', 'reason' => 'Conditions not met'];
        }
        
        if ($this->parallel) {
            // Execute steps in parallel
            $results = $this->executeParallel($context);
        } else {
            // Execute steps sequentially
            $results = $this->executeSequential($context);
        }
        
        return [
            'status' => 'completed',
            'results' => $results,
            'duration' => $this->calculateDuration($results)
        ];
    }
    
    private function executeParallel($context)
    {
        $promises = [];
        $results = [];
        
        foreach ($this->steps as $stepConfig) {
            $step = new Step($stepConfig);
            $promises[] = $step->executeAsync($context);
        }
        
        // Wait for all steps to complete
        foreach ($promises as $promise) {
            $results[] = $promise->wait();
        }
        
        return $results;
    }
    
    private function executeSequential($context)
    {
        $results = [];
        
        foreach ($this->steps as $stepConfig) {
            $step = new Step($stepConfig);
            $result = $step->execute($context);
            $results[] = $result;
            
            // Stop execution if step fails and fail_fast is enabled
            if (!$result['success'] && $stepConfig['fail_fast'] ?? false) {
                break;
            }
        }
        
        return $results;
    }
    
    private function evaluateConditions($context)
    {
        foreach ($this->conditions as $condition) {
            if (!$this->evaluateCondition($condition, $context)) {
                return false;
            }
        }
        return true;
    }
    
    private function evaluateCondition($condition, $context)
    {
        $type = $condition['type'] ?? 'expression';
        
        switch ($type) {
            case 'expression':
                return $this->evaluateExpression($condition['expression'], $context);
            case 'variable':
                return $this->evaluateVariable($condition['variable'], $condition['value'], $context);
            case 'branch':
                return $this->evaluateBranch($condition['branch'], $context);
            default:
                return true;
        }
    }
}
```

## Continuous Integration

### CI Manager
Manage CI processes and workflows:
```php
class CiManager
{
    private $sourceIntegration;
    private $builder;
    private $tester;
    private $codeQualityChecker;
    private $artifactManager;
    
    public function runCiPipeline($pipelineConfig)
    {
        $executionId = uniqid('ci_');
        $startTime = time();
        
        try {
            // 1. Source code integration
            $sourceResult = $this->sourceIntegration->fetchSource($pipelineConfig['source']);
            
            // 2. Build application
            $buildResult = $this->builder->build($sourceResult['workspace']);
            
            // 3. Run tests
            $testResult = $this->tester->runTests($sourceResult['workspace']);
            
            // 4. Code quality checks
            $qualityResult = $this->codeQualityChecker->checkQuality($sourceResult['workspace']);
            
            // 5. Security scanning
            $securityResult = $this->securityScanner->scan($sourceResult['workspace']);
            
            // 6. Create artifacts
            $artifacts = $this->artifactManager->createArtifacts($sourceResult['workspace']);
            
            $endTime = time();
            
            return [
                'execution_id' => $executionId,
                'status' => 'success',
                'duration' => $endTime - $startTime,
                'results' => [
                    'source' => $sourceResult,
                    'build' => $buildResult,
                    'test' => $testResult,
                    'quality' => $qualityResult,
                    'security' => $securityResult,
                    'artifacts' => $artifacts
                ]
            ];
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
}
```

### Builder
Handle application building:
```php
class Builder
{
    public function build($workspace)
    {
        $startTime = time();
        
        // Change to workspace directory
        chdir($workspace);
        
        // Run build commands based on project type
        $projectType = $this->detectProjectType($workspace);
        $buildCommands = $this->getBuildCommands($projectType);
        
        $buildLogs = [];
        $success = true;
        
        foreach ($buildCommands as $command) {
            $result = $this->executeCommand($command);
            $buildLogs[] = [
                'command' => $command,
                'output' => $result['output'],
                'exit_code' => $result['exit_code'],
                'timestamp' => time()
            ];
            
            if ($result['exit_code'] !== 0) {
                $success = false;
                break;
            }
        }
        
        $endTime = time();
        
        return [
            'success' => $success,
            'duration' => $endTime - $startTime,
            'logs' => $buildLogs,
            'artifacts_path' => $success ? $this->getArtifactsPath($workspace) : null
        ];
    }
    
    private function detectProjectType($workspace)
    {
        if (file_exists($workspace . '/composer.json')) {
            return 'php';
        } elseif (file_exists($workspace . '/package.json')) {
            if (file_exists($workspace . '/vite.config.js')) {
                return 'react-vite';
            } elseif (file_exists($workspace . '/next.config.js')) {
                return 'nextjs';
            } else {
                return 'nodejs';
            }
        } elseif (file_exists($workspace . '/pom.xml')) {
            return 'java-maven';
        } elseif (file_exists($workspace . '/build.gradle')) {
            return 'java-gradle';
        }
        
        return 'unknown';
    }
    
    private function getBuildCommands($projectType)
    {
        switch ($projectType) {
            case 'php':
                return [
                    'composer install --no-dev',
                    'php vendor/bin/phpunit'
                ];
            case 'react-vite':
                return [
                    'npm install',
                    'npm run build'
                ];
            case 'nextjs':
                return [
                    'npm install',
                    'npm run build'
                ];
            case 'java-maven':
                return [
                    'mvn clean package'
                ];
            case 'java-gradle':
                return [
                    './gradlew build'
                ];
            default:
                return ['echo "Unknown project type"'];
        }
    }
}
```

## Configuration Example
```php
[
    'pipelines' => [
        'defaults' => [
            'timeout' => 3600, // 1 hour
            'retry_attempts' => 3,
            'parallelism' => 4
        ],
        'triggers' => [
            'webhook' => [
                'enabled' => true,
                'secret' => env('PIPELINE_WEBHOOK_SECRET'),
                'events' => ['push', 'pull_request']
            ],
            'schedule' => [
                'enabled' => true,
                'cron' => '0 2 * * *' // Daily at 2 AM
            ],
            'manual' => [
                'enabled' => true,
                'roles' => ['admin', 'developer']
            ]
        ]
    ],
    'stages' => [
        'build' => [
            'timeout' => 1800, // 30 minutes
            'steps' => [
                'checkout' => [
                    'type' => 'git',
                    'action' => 'clone',
                    'repository' => '${GIT_REPO}',
                    'branch' => '${GIT_BRANCH}'
                ],
                'dependencies' => [
                    'type' => 'shell',
                    'script' => [
                        'composer install',
                        'npm install'
                    ]
                ],
                'compile' => [
                    'type' => 'shell',
                    'script' => [
                        'npm run build',
                        'composer dump-autoload --optimize'
                    ]
                ]
            ]
        ],
        'test' => [
            'timeout' => 1800, // 30 minutes
            'steps' => [
                'unit_tests' => [
                    'type' => 'shell',
                    'script' => 'php vendor/bin/phpunit'
                ],
                'integration_tests' => [
                    'type' => 'shell',
                    'script' => 'php vendor/bin/phpunit --testsuite integration'
                ],
                'frontend_tests' => [
                    'type' => 'shell',
                    'script' => 'npm run test'
                ]
            ]
        ],
        'quality' => [
            'timeout' => 900, // 15 minutes
            'steps' => [
                'code_analysis' => [
                    'type' => 'shell',
                    'script' => 'php vendor/bin/phpcs'
                ],
                'security_scan' => [
                    'type' => 'shell',
                    'script' => 'php vendor/bin/security-checker security:check'
                ]
            ]
        ],
        'deploy' => [
            'timeout' => 1800, // 30 minutes
            'environment' => '${TARGET_ENV}',
            'steps' => [
                'prepare' => [
                    'type' => 'shell',
                    'script' => 'echo "Preparing deployment to ${TARGET_ENV}"'
                ],
                'deploy_backend' => [
                    'type' => 'deploy',
                    'target' => 'backend',
                    'strategy' => 'rolling_update'
                ],
                'deploy_frontend' => [
                    'type' => 'deploy',
                    'target' => 'frontend',
                    'strategy' => 'blue_green'
                ],
                'validate' => [
                    'type' => 'shell',
                    'script' => [
                        'curl -f http://backend-${TARGET_ENV}/health',
                        'curl -f http://frontend-${TARGET_ENV}/health'
                    ]
                ]
            ]
        ]
    ],
    'notifications' => [
        'channels' => [
            'slack' => [
                'enabled' => true,
                'webhook_url' => env('SLACK_WEBHOOK_URL'),
                'channels' => [
                    'success' => '#deployments',
                    'failure' => '#alerts'
                ]
            ],
            'email' => [
                'enabled' => true,
                'smtp' => [
                    'host' => env('SMTP_HOST'),
                    'port' => env('SMTP_PORT', 587),
                    'username' => env('SMTP_USERNAME'),
                    'password' => env('SMTP_PASSWORD')
                ],
                'recipients' => [
                    'success' => ['team@example.com'],
                    'failure' => ['team@example.com', 'ops@example.com']
                ]
            ]
        ]
    ],
    'artifacts' => [
        'storage' => 's3',
        's3' => [
            'bucket' => env('ARTIFACTS_BUCKET'),
            'region' => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY')
            ]
        ],
        'retention' => 86400 * 30 // 30 days
    ],
    'security' => [
        'enabled' => true,
        'scan_on_build' => true,
        'fail_on_vulnerabilities' => true,
        'vulnerability_threshold' => 'high'
    ],
    'monitoring' => [
        'metrics' => [
            'execution_time' => true,
            'success_rate' => true,
            'resource_usage' => true
        ],
        'alerts' => [
            'pipeline_failure' => true,
            'long_execution' => true,
            'resource_exhaustion' => true
        ]
    ]
]
```

## Pipeline Templates
```php
class PipelineTemplate
{
    private $name;
    private $description;
    private $parameters;
    private $pipeline;
    
    public function __construct($config)
    {
        $this->name = $config['name'];
        $this->description = $config['description'] ?? '';
        $this->parameters = $config['parameters'] ?? [];
        $this->pipeline = $config['pipeline'] ?? [];
    }
    
    public function instantiate($parameters = [])
    {
        // Validate required parameters
        foreach ($this->parameters as $param) {
            if ($param['required'] && !isset($parameters[$param['name']])) {
                throw new MissingParameterException("Required parameter {$param['name']} is missing");
            }
        }
        
        // Merge default values
        $mergedParams = array_merge(
            $this->getDefaultParameters(),
            $parameters
        );
        
        // Substitute parameters in pipeline definition
        $instantiatedPipeline = $this->substituteParameters(
            $this->pipeline,
            $mergedParams
        );
        
        return new PipelineDefinition($instantiatedPipeline);
    }
    
    private function getDefaultParameters()
    {
        $defaults = [];
        foreach ($this->parameters as $param) {
            if (isset($param['default'])) {
                $defaults[$param['name']] = $param['default'];
            }
        }
        return $defaults;
    }
    
    private function substituteParameters($definition, $parameters)
    {
        $json = json_encode($definition);
        
        foreach ($parameters as $key => $value) {
            $json = str_replace('${' . $key . '}', $value, $json);
        }
        
        return json_decode($json, true);
    }
}

// Example template usage
$phpAppTemplate = new PipelineTemplate([
    'name' => 'php-app',
    'description' => 'Template for PHP applications',
    'parameters' => [
        [
            'name' => 'app_name',
            'required' => true,
            'description' => 'Application name'
        ],
        [
            'name' => 'git_repo',
            'required' => true,
            'description' => 'Git repository URL'
        ],
        [
            'name' => 'target_env',
            'required' => false,
            'default' => 'staging',
            'description' => 'Target environment'
        ]
    ],
    'pipeline' => [
        'name' => '${app_name}-pipeline',
        'stages' => [
            [
                'name' => 'build',
                'steps' => [
                    [
                        'type' => 'git',
                        'action' => 'clone',
                        'repository' => '${git_repo}'
                    ],
                    [
                        'type' => 'shell',
                        'script' => 'composer install'
                    ]
                ]
            ],
            [
                'name' => 'test',
                'steps' => [
                    [
                        'type' => 'shell',
                        'script' => 'php vendor/bin/phpunit'
                    ]
                ]
            ],
            [
                'name' => 'deploy',
                'environment' => '${target_env}',
                'steps' => [
                    [
                        'type' => 'deploy',
                        'target' => '${app_name}',
                        'strategy' => 'rolling_update'
                    ]
                ]
            ]
        ]
    ]
]);
```

## API Endpoints
```
# CI/CD Pipeline Management
POST   /pipelines                   # Create new pipeline
GET    /pipelines                  # List all pipelines
GET    /pipelines/{id}             # Get pipeline details
PUT    /pipelines/{id}             # Update pipeline
DELETE /pipelines/{id}             # Delete pipeline
POST   /pipelines/{id}/execute      # Execute pipeline
GET    /pipelines/{id}/executions   # List pipeline executions
GET    /pipelines/{id}/executions/{exec_id} # Get execution details
POST   /pipelines/{id}/executions/{exec_id}/cancel # Cancel execution
GET    /pipelines/{id}/executions/{exec_id}/logs # Get execution logs
GET    /pipelines/templates         # List pipeline templates
POST   /pipelines/templates         # Create pipeline template
GET    /pipelines/templates/{name}  # Get template details
PUT    /pipelines/templates/{name}  # Update template
DELETE /pipelines/templates/{name}  # Delete template
GET    /pipelines/triggers          # List pipeline triggers
POST   /pipelines/triggers          # Create pipeline trigger
GET    /pipelines/triggers/{id}     # Get trigger details
PUT    /pipelines/triggers/{id}     # Update trigger
DELETE /pipelines/triggers/{id}     # Delete trigger
GET    /pipelines/artifacts         # List artifacts
GET    /pipelines/artifacts/{id}    # Get artifact details
DELETE /pipelines/artifacts/{id}    # Delete artifact
GET    /pipelines/status            # Get pipeline system status
GET    /pipelines/metrics           # Get pipeline metrics
GET    /pipelines/analytics         # Get pipeline analytics
```

## Pipeline Execution Response
```json
{
  "execution": {
    "id": "exec_5f4d4a4b4c4d4e4f50515253",
    "pipeline_id": "pipe_5f4d4a4b4c4d4e4f50515254",
    "pipeline_name": "my-app-pipeline",
    "status": "running",
    "trigger": {
      "type": "webhook",
      "source": "github",
      "event": "push",
      "branch": "main"
    },
    "stages": [
      {
        "name": "build",
        "status": "completed",
        "start_time": "2023-01-01T10:00:00Z",
        "end_time": "2023-01-01T10:05:00Z",
        "duration": 300,
        "steps": [
          {
            "name": "checkout",
            "status": "completed",
            "start_time": "2023-01-01T10:00:00Z",
            "end_time": "2023-01-01T10:01:00Z",
            "duration": 60
          },
          {
            "name": "dependencies",
            "status": "completed",
            "start_time": "2023-01-01T10:01:00Z",
            "end_time": "2023-01-01T10:03:00Z",
            "duration": 120
          }
        ]
      },
      {
        "name": "test",
        "status": "running",
        "start_time": "2023-01-01T10:05:00Z",
        "end_time": null,
        "duration": null,
        "steps": [
          {
            "name": "unit_tests",
            "status": "running",
            "start_time": "2023-01-01T10:05:00Z",
            "end_time": null,
            "duration": null
          }
        ]
      }
    ],
    "created_at": "2023-01-01T10:00:00Z"
  }
}
```

## Pipeline Metrics Response
```json
{
  "metrics": {
    "total_executions": 1250,
    "successful_executions": 1180,
    "failed_executions": 70,
    "success_rate": 94.4,
    "average_duration": 1200,
    "median_duration": 1150,
    "p95_duration": 1800,
    "executions_per_day": 42,
    "most_active_pipelines": [
      {
        "name": "web-app-pipeline",
        "executions": 450,
        "success_rate": 96.2
      },
      {
        "name": "api-pipeline",
        "executions": 320,
        "success_rate": 92.8
      }
    ],
    "resource_usage": {
      "avg_cpu_utilization": 65,
      "avg_memory_usage": "2.4GB",
      "peak_concurrent_executions": 8
    }
  },
  "timestamp": "2023-01-01T10:00:00Z"
}
```

## Evaluation Criteria
- [ ] Pipeline definition and configuration work
- [ ] Stage and step management function
- [ ] Parallel execution operates correctly
- [ ] Conditional execution works as expected
- [ ] Source code integration successful
- [ ] Automated building functions
- [ ] Testing automation operates
- [ ] Code quality checks work
- [ ] Security scanning implemented
- [ ] Deployment automation successful
- [ ] Environment management functions
- [ ] Rollback capabilities work
- [ ] Pipeline templates and reusability implemented
- [ ] Pipeline visualization available
- [ ] Pipeline monitoring and alerts function
- [ ] Code is well-organized and documented
- [ ] Tests cover CI/CD pipeline functionality

## Resources
- [CI/CD Pipeline Concepts](https://martinfowler.com/bliki/ContinuousDelivery.html)
- [GitHub Actions](https://github.com/features/actions)
- [GitLab CI/CD](https://docs.gitlab.com/ee/ci/)
- [Jenkins](https://www.jenkins.io/)
- [CircleCI](https://circleci.com/)