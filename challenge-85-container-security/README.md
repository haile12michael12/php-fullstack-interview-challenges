# Challenge 85: Container Security

## Description
In this challenge, you'll implement comprehensive container security measures to protect containerized applications from vulnerabilities and threats. Container security is critical for maintaining the integrity and confidentiality of applications running in containerized environments.

## Learning Objectives
- Understand container security concepts and best practices
- Implement image scanning and vulnerability management
- Create runtime security monitoring
- Handle network security and isolation
- Manage access control and authentication
- Implement secrets management
- Create security policies and compliance

## Requirements
Create a container security system with the following features:

1. **Image Security**:
   - Image scanning and vulnerability detection
   - Base image hardening
   - Image signing and verification
   - Registry security
   - Dependency scanning
   - CVE monitoring

2. **Runtime Security**:
   - Container runtime monitoring
   - Anomaly detection
   - Process and file system monitoring
   - Network traffic monitoring
   - Privilege escalation prevention
   - Container escape detection

3. **Access Control**:
   - Role-based access control (RBAC)
   - Authentication and authorization
   - Service account management
   - Namespace isolation
   - Pod security policies
   - Network policies

4. **Advanced Features**:
   - Secrets management
   - Encryption at rest and in transit
   - Compliance monitoring
   - Security auditing
   - Incident response
   - Threat intelligence integration

## Features to Implement
- [ ] Image scanning and vulnerability detection
- [ ] Base image hardening
- [ ] Image signing and verification
- [ ] Runtime security monitoring
- [ ] Anomaly detection
- [ ] Network security and isolation
- [ ] Role-based access control (RBAC)
- [ ] Secrets management
- [ ] Encryption at rest and in transit
- [ ] Compliance monitoring
- [ ] Security auditing
- [ ] Incident response

## Project Structure
```
backend-php/
├── src/
│   ├── Security/
│   │   ├── ContainerSecurity.php
│   │   ├── Image/
│   │   │   ├── ImageScanner.php
│   │   │   ├── VulnerabilityDetector.php
│   │   │   ├── ImageSigner.php
│   │   │   └── RegistrySecurity.php
│   │   ├── Runtime/
│   │   │   ├── RuntimeMonitor.php
│   │   │   ├── AnomalyDetector.php
│   │   │   ├── ProcessMonitor.php
│   │   │   └── NetworkMonitor.php
│   │   ├── Access/
│   │   │   ├── RbacManager.php
│   │   │   ├── AuthManager.php
│   │   │   ├── ServiceAccountManager.php
│   │   │   └── PolicyEnforcer.php
│   │   ├── Secrets/
│   │   │   ├── SecretManager.php
│   │   │   ├── EncryptionManager.php
│   │   │   └── KeyManager.php
│   │   └── Compliance/
│   │       ├── ComplianceChecker.php
│   │       ├── AuditLogger.php
│   │       └── IncidentResponse.php
│   └── Services/
├── public/
│   └── index.php
├── security/
│   ├── policies/
│   │   ├── pod-security-policy.yaml
│   │   ├── network-policy.yaml
│   │   └── rbac-policy.yaml
│   ├── scans/
│   │   └── vulnerability-reports/
│   ├── audits/
│   │   └── audit-logs/
│   └── incidents/
├── storage/
│   └── security/
├── config/
│   └── security.php
└── vendor/

frontend-react/
├── src/
│   ├── api/
│   │   └── security.js
│   ├── components/
│   │   ├── Dashboard/
│   │   │   ├── SecurityOverview.jsx
│   │   │   ├── VulnerabilityReport.jsx
│   │   │   └── ComplianceStatus.jsx
│   │   ├── Scans/
│   │   │   ├── ImageScanList.jsx
│   │   │   └── ScanDetail.jsx
│   │   ├── Monitoring/
│   │   │   ├── RuntimeAlerts.jsx
│   │   │   └── NetworkActivity.jsx
│   │   └── Incidents/
│   │       ├── IncidentList.jsx
│   │       └── IncidentDetail.jsx
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

## Image Security

### Image Scanner
Scan container images for vulnerabilities:
```php
class ImageScanner
{
    private $vulnerabilityDatabase;
    private $scannerEngine;
    
    public function scanImage($image, $tag = 'latest')
    {
        $imageName = "$image:$tag";
        
        // Pull the image if not present
        $this->pullImage($imageName);
        
        // Extract image layers
        $layers = $this->extractImageLayers($imageName);
        
        // Scan each layer for vulnerabilities
        $vulnerabilities = [];
        foreach ($layers as $layer) {
            $layerVulns = $this->scanLayer($layer);
            $vulnerabilities = array_merge($vulnerabilities, $layerVulns);
        }
        
        // Check against vulnerability database
        $filteredVulns = $this->filterVulnerabilities($vulnerabilities);
        
        // Generate report
        $report = $this->generateReport($imageName, $filteredVulns);
        
        return $report;
    }
    
    public function scanRegistry($registry, $repository)
    {
        $tags = $this->getRegistryTags($registry, $repository);
        $reports = [];
        
        foreach ($tags as $tag) {
            $reports[] = $this->scanImage("$registry/$repository", $tag);
        }
        
        return $reports;
    }
    
    private function scanLayer($layer)
    {
        // Scan layer filesystem for known vulnerabilities
        $packages = $this->extractPackages($layer);
        $vulnerabilities = [];
        
        foreach ($packages as $package) {
            $packageVulns = $this->vulnerabilityDatabase->findVulnerabilities(
                $package['name'], 
                $package['version']
            );
            $vulnerabilities = array_merge($vulnerabilities, $packageVulns);
        }
        
        return $vulnerabilities;
    }
    
    private function filterVulnerabilities($vulnerabilities)
    {
        $filtered = [];
        
        foreach ($vulnerabilities as $vuln) {
            // Filter by severity threshold
            if ($this->isSeverityAboveThreshold($vuln['severity'])) {
                $filtered[] = $vuln;
            }
            
            // Filter by CVSS score
            if ($vuln['cvss_score'] >= config('security.cvss_threshold', 7.0)) {
                $filtered[] = $vuln;
            }
        }
        
        return $filtered;
    }
    
    private function generateReport($imageName, $vulnerabilities)
    {
        $severityCounts = [
            'critical' => 0,
            'high' => 0,
            'medium' => 0,
            'low' => 0
        ];
        
        foreach ($vulnerabilities as $vuln) {
            $severityCounts[$vuln['severity']]++;
        }
        
        return [
            'image' => $imageName,
            'scan_time' => time(),
            'total_vulnerabilities' => count($vulnerabilities),
            'severity_counts' => $severityCounts,
            'vulnerabilities' => $vulnerabilities,
            'recommendations' => $this->generateRecommendations($vulnerabilities)
        ];
    }
}
```

### Image Hardening
Create secure base images:
```php
class ImageHardening
{
    public function createHardenedImage($baseImage, $config)
    {
        // Create Dockerfile with security best practices
        $dockerfile = $this->generateHardenedDockerfile($baseImage, $config);
        
        // Build the hardened image
        $imageName = $this->buildImage($dockerfile, $config['name']);
        
        // Sign the image
        $this->signImage($imageName, $config['signing_key']);
        
        return $imageName;
    }
    
    private function generateHardenedDockerfile($baseImage, $config)
    {
        $dockerfile = [
            "FROM $baseImage",
            "LABEL security.hardened=true",
            "LABEL security.scanned=$(date +%s)"
        ];
        
        // Use non-root user
        if (isset($config['non_root_user'])) {
            $user = $config['non_root_user'];
            $dockerfile[] = "RUN addgroup -g {$user['gid']} {$user['name']} && \\";
            $dockerfile[] = "    adduser -D -u {$user['uid']} -G {$user['name']} {$user['name']}";
            $dockerfile[] = "USER {$user['name']}";
        }
        
        // Remove unnecessary packages
        if (isset($config['remove_packages'])) {
            $packages = implode(' ', $config['remove_packages']);
            $dockerfile[] = "RUN apk del $packages || yum remove -y $packages || apt-get remove -y $packages";
        }
        
        // Update packages to latest versions
        $dockerfile[] = "RUN apk update && apk upgrade || \\";
        $dockerfile[] = "    yum update -y || \\";
        $dockerfile[] = "    apt-get update && apt-get upgrade -y";
        
        // Set file permissions
        if (isset($config['file_permissions'])) {
            foreach ($config['file_permissions'] as $path => $permission) {
                $dockerfile[] = "RUN chmod $permission $path";
            }
        }
        
        // Add security scanning label
        $dockerfile[] = "LABEL security.scan.base=true";
        
        return implode("\n", $dockerfile);
    }
}
```

## Runtime Security

### Runtime Monitor
Monitor container runtime for security issues:
```php
class RuntimeMonitor
{
    private $anomalyDetector;
    private $processMonitor;
    private $networkMonitor;
    private $alertManager;
    
    public function startMonitoring()
    {
        while (true) {
            // Monitor running containers
            $containers = $this->getRunningContainers();
            
            foreach ($containers as $container) {
                // Monitor processes
                $processes = $this->processMonitor->getContainerProcesses($container);
                $this->detectSuspiciousProcesses($processes, $container);
                
                // Monitor network activity
                $networkActivity = $this->networkMonitor->getContainerNetworkActivity($container);
                $this->detectSuspiciousNetworkActivity($networkActivity, $container);
                
                // Monitor file system changes
                $fsChanges = $this->getFilesystemChanges($container);
                $this->detectSuspiciousFileChanges($fsChanges, $container);
            }
            
            sleep(config('security.monitoring_interval', 30));
        }
    }
    
    private function detectSuspiciousProcesses($processes, $container)
    {
        foreach ($processes as $process) {
            // Check for known malicious processes
            if ($this->isKnownMaliciousProcess($process)) {
                $alert = new SecurityAlert(
                    'malicious_process_detected',
                    $container,
                    "Suspicious process detected: {$process['command']}",
                    'high'
                );
                $this->alertManager->sendAlert($alert);
            }
            
            // Check for privilege escalation attempts
            if ($this->isPrivilegeEscalationAttempt($process)) {
                $alert = new SecurityAlert(
                    'privilege_escalation_attempt',
                    $container,
                    "Possible privilege escalation: {$process['command']}",
                    'critical'
                );
                $this->alertManager->sendAlert($alert);
            }
        }
    }
    
    private function detectSuspiciousNetworkActivity($networkActivity, $container)
    {
        foreach ($networkActivity as $connection) {
            // Check for connections to known malicious IPs
            if ($this->isMaliciousIP($connection['remote_ip'])) {
                $alert = new SecurityAlert(
                    'malicious_network_connection',
                    $container,
                    "Connection to malicious IP: {$connection['remote_ip']}",
                    'high'
                );
                $this->alertManager->sendAlert($alert);
            }
            
            // Check for unusual outbound traffic
            if ($this->isUnusualTraffic($connection)) {
                $alert = new SecurityAlert(
                    'unusual_network_traffic',
                    $container,
                    "Unusual network traffic detected",
                    'medium'
                );
                $this->alertManager->sendAlert($alert);
            }
        }
    }
}
```

## Configuration Example
```php
[
    'image_scanning' => [
        'enabled' => true,
        'interval' => 3600, // 1 hour
        'thresholds' => [
            'critical' => 0,
            'high' => 1,
            'medium' => 5,
            'low' => 10
        ],
        'registries' => [
            'dockerhub' => [
                'url' => 'https://registry-1.docker.io',
                'username' => env('DOCKERHUB_USERNAME'),
                'password' => env('DOCKERHUB_PASSWORD')
            ],
            'private' => [
                'url' => 'https://registry.internal',
                'username' => env('PRIVATE_REGISTRY_USERNAME'),
                'password' => env('PRIVATE_REGISTRY_PASSWORD')
            ]
        ],
        'exclusions' => [
            'images' => ['alpine:latest'],
            'vulnerabilities' => ['CVE-2023-XXXX']
        ]
    ],
    'runtime_monitoring' => [
        'enabled' => true,
        'interval' => 30,
        'process_monitoring' => true,
        'network_monitoring' => true,
        'filesystem_monitoring' => true,
        'anomaly_detection' => true,
        'suspicious_processes' => [
            'miner_processes' => ['xmrig', 'cpuminer'],
            'reverse_shells' => ['nc', 'ncat', 'netcat'],
            'exploitation_tools' => ['metasploit', 'sqlmap']
        ]
    ],
    'access_control' => [
        'rbac' => [
            'enabled' => true,
            'default_role' => 'viewer',
            'roles' => [
                'admin' => [
                    'permissions' => ['*']
                ],
                'developer' => [
                    'permissions' => [
                        'deployments:create',
                        'deployments:update',
                        'deployments:delete',
                        'pods:read',
                        'pods:delete'
                    ]
                ],
                'viewer' => [
                    'permissions' => [
                        'deployments:read',
                        'pods:read',
                        'services:read'
                    ]
                ]
            ]
        ],
        'authentication' => [
            'method' => 'oauth2',
            'providers' => [
                'github' => [
                    'client_id' => env('GITHUB_CLIENT_ID'),
                    'client_secret' => env('GITHUB_CLIENT_SECRET')
                ],
                'google' => [
                    'client_id' => env('GOOGLE_CLIENT_ID'),
                    'client_secret' => env('GOOGLE_CLIENT_SECRET')
                ]
            ]
        ]
    ],
    'secrets_management' => [
        'backend' => 'vault',
        'vault' => [
            'address' => 'https://vault.internal',
            'token' => env('VAULT_TOKEN'),
            'mount_path' => 'secret'
        ],
        'encryption' => [
            'enabled' => true,
            'algorithm' => 'AES-256-GCM',
            'key_rotation' => 86400 // 24 hours
        ]
    ],
    'network_security' => [
        'network_policies' => [
            'enabled' => true,
            'default_policy' => 'deny-all',
            'rules' => [
                'allow-web-ingress' => [
                    'from' => ['namespace' => 'ingress'],
                    'to' => ['app' => 'web'],
                    'ports' => [80, 443]
                ],
                'allow-api-internal' => [
                    'from' => ['namespace' => 'default'],
                    'to' => ['app' => 'api'],
                    'ports' => [8080]
                ]
            ]
        ],
        'service_mesh' => [
            'enabled' => true,
            'mtls' => true,
            'certificate_authority' => 'internal-ca'
        ]
    ],
    'compliance' => [
        'standards' => ['cis', 'nist', 'gdpr'],
        'audit_interval' => 3600,
        'retention_period' => 2592000, // 30 days
        'incident_response' => [
            'enabled' => true,
            'notification_channels' => ['slack', 'email'],
            'escalation_policy' => [
                'low' => ['email'],
                'medium' => ['email', 'slack'],
                'high' => ['email', 'slack', 'sms'],
                'critical' => ['email', 'slack', 'sms', 'phone']
            ]
        ]
    ]
]
```

## Security Policy Enforcement
```php
class PolicyEnforcer
{
    private $policies;
    private $violationHandler;
    
    public function enforcePolicies($resource, $action)
    {
        $violations = [];
        
        foreach ($this->policies as $policy) {
            $violation = $policy->validate($resource, $action);
            if ($violation) {
                $violations[] = $violation;
            }
        }
        
        if (!empty($violations)) {
            $this->handleViolations($violations, $resource, $action);
            return false;
        }
        
        return true;
    }
    
    public function addPolicy($policy)
    {
        $this->policies[] = $policy;
    }
    
    private function handleViolations($violations, $resource, $action)
    {
        foreach ($violations as $violation) {
            $alert = new PolicyViolationAlert(
                $violation['type'],
                $resource,
                $action,
                $violation['message'],
                $violation['severity']
            );
            
            $this->violationHandler->handle($alert);
        }
    }
}

class PodSecurityPolicy
{
    public function validate($pod, $action)
    {
        // Check if pod is running as root
        if ($this->isRunningAsRoot($pod)) {
            return [
                'type' => 'running_as_root',
                'message' => 'Pod is running as root user',
                'severity' => 'high'
            ];
        }
        
        // Check for privileged containers
        if ($this->hasPrivilegedContainers($pod)) {
            return [
                'type' => 'privileged_container',
                'message' => 'Pod contains privileged containers',
                'severity' => 'critical'
            ];
        }
        
        // Check for host PID/IPC sharing
        if ($this->sharesHostNamespace($pod)) {
            return [
                'type' => 'host_namespace_sharing',
                'message' => 'Pod shares host PID or IPC namespace',
                'severity' => 'high'
            ];
        }
        
        // Check for allowed volumes
        if (!$this->hasAllowedVolumes($pod)) {
            return [
                'type' => 'unauthorized_volume',
                'message' => 'Pod uses unauthorized volume types',
                'severity' => 'medium'
            ];
        }
        
        return null;
    }
    
    private function isRunningAsRoot($pod)
    {
        $securityContext = $pod['spec']['securityContext'] ?? [];
        $runAsUser = $securityContext['runAsUser'] ?? 0;
        return $runAsUser === 0;
    }
    
    private function hasPrivilegedContainers($pod)
    {
        foreach ($pod['spec']['containers'] as $container) {
            $securityContext = $container['securityContext'] ?? [];
            if (isset($securityContext['privileged']) && $securityContext['privileged']) {
                return true;
            }
        }
        return false;
    }
}
```

## API Endpoints
```
# Container Security Management
POST   /security/scan/image              # Scan container image
GET    /security/scan/images             # List image scans
GET    /security/scan/images/{id}        # Get image scan details
POST   /security/scan/registry           # Scan registry images
GET    /security/vulnerabilities         # List vulnerabilities
GET    /security/vulnerabilities/{id}    # Get vulnerability details
POST   /security/policies/enforce        # Enforce security policies
GET    /security/policies                # List security policies
POST   /security/policies                # Create security policy
GET    /security/policies/{name}         # Get policy details
PUT    /security/policies/{name}         # Update policy
DELETE /security/policies/{name}         # Delete policy
GET    /security/runtime/alerts          # List runtime security alerts
POST   /security/runtime/alerts/acknowledge # Acknowledge alert
GET    /security/runtime/monitoring      # Get runtime monitoring status
GET    /security/access/rbac             # Get RBAC configuration
POST   /security/access/rbac             # Update RBAC configuration
GET    /security/secrets                 # List secrets
POST   /security/secrets                 # Create secret
GET    /security/secrets/{name}          # Get secret details
DELETE /security/secrets/{name}          # Delete secret
GET    /security/compliance/reports      # Get compliance reports
POST   /security/compliance/audit        # Trigger compliance audit
GET    /security/incidents               # List security incidents
POST   /security/incidents/{id}/resolve  # Resolve incident
```

## Security Scan Report Response
```json
{
  "image": "myapp/api:latest",
  "scan_time": "2023-01-01T10:00:00Z",
  "total_vulnerabilities": 15,
  "severity_counts": {
    "critical": 1,
    "high": 3,
    "medium": 7,
    "low": 4
  },
  "vulnerabilities": [
    {
      "id": "CVE-2023-12345",
      "package": "openssl",
      "installed_version": "1.1.1k",
      "fixed_version": "1.1.1l",
      "severity": "high",
      "cvss_score": 7.5,
      "description": "Buffer overflow in OpenSSL",
      "published_date": "2023-01-01",
      "references": [
        "https://cve.mitre.org/cgi-bin/cvename.cgi?name=CVE-2023-12345"
      ]
    }
  ],
  "recommendations": [
    "Upgrade openssl package to version 1.1.1l or later",
    "Rebuild image with updated base image",
    "Scan image again after remediation"
  ],
  "status": "failed",
  "threshold_exceeded": {
    "high": 3,
    "threshold": 1
  }
}
```

## Runtime Security Alert Response
```json
{
  "alerts": [
    {
      "id": "alert_5f4d4a4b4c4d4e4f50515253",
      "type": "malicious_process_detected",
      "container": "api-7d5b8c9c4-xl2v9",
      "message": "Suspicious process detected: xmrig",
      "severity": "critical",
      "timestamp": "2023-01-01T09:59:30Z",
      "status": "active",
      "details": {
        "process_id": 1234,
        "command": "/tmp/xmrig",
        "parent_process": "sh",
        "user": "www-data"
      }
    },
    {
      "id": "alert_5f4d4a4b4c4d4e4f50515254",
      "type": "unusual_network_traffic",
      "container": "web-7d5b8c9c4-xl2v9",
      "message": "Unusual network traffic detected",
      "severity": "medium",
      "timestamp": "2023-01-01T09:58:45Z",
      "status": "acknowledged",
      "details": {
        "remote_ip": "192.168.1.100",
        "port": 4444,
        "protocol": "tcp",
        "bytes_transferred": 1024000
      }
    }
  ]
}
```

## Evaluation Criteria
- [ ] Image scanning and vulnerability detection work
- [ ] Base image hardening creates secure images
- [ ] Image signing and verification function
- [ ] Runtime security monitoring detects threats
- [ ] Anomaly detection identifies suspicious activity
- [ ] Network security and isolation work
- [ ] Role-based access control (RBAC) enforces policies
- [ ] Secrets management secures sensitive data
- [ ] Encryption at rest and in transit functions
- [ ] Compliance monitoring tracks standards
- [ ] Security auditing logs activities
- [ ] Incident response handles security events
- [ ] Code is well-organized and documented
- [ ] Tests cover security functionality

## Resources
- [Docker Security Best Practices](https://docs.docker.com/engine/security/)
- [Kubernetes Security](https://kubernetes.io/docs/concepts/security/)
- [CIS Docker Benchmark](https://www.cisecurity.org/benchmark/docker/)
- [OWASP Container Security](https://cheatsheetseries.owasp.org/cheatsheets/Container_Security_Cheat_Sheet.html)