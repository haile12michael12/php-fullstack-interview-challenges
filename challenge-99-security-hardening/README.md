# Challenge 99: Security Hardening

## Description
In this challenge, you'll implement advanced security measures for PHP applications. Security hardening is the process of securing applications by reducing vulnerabilities and attack surfaces. You'll build comprehensive security tools that protect against common threats like injection attacks, authentication bypass, data exposure, and more, while implementing advanced security patterns and monitoring capabilities.

## Learning Objectives
- Understand advanced security concepts and threat modeling
- Implement comprehensive input validation and sanitization
- Build advanced authentication and authorization systems
- Create security monitoring and alerting tools
- Implement encryption and secure data handling
- Build intrusion detection and prevention systems
- Understand compliance and security auditing
- Implement secure coding practices

## Requirements

### Core Features
1. **Input Validation and Sanitization**
   - Implement comprehensive input validation
   - Create secure data sanitization pipelines
   - Build type-safe validation rules
   - Implement content security policies
   - Create secure file upload handling

2. **Authentication and Authorization**
   - Implement multi-factor authentication
   - Build secure session management
   - Create role-based access control
   - Implement attribute-based access control
   - Build secure password handling systems

3. **Data Protection**
   - Implement encryption at rest and in transit
   - Create secure key management systems
   - Build data masking and anonymization
   - Implement secure data disposal
   - Create audit trails for sensitive operations

4. **Security Monitoring**
   - Build intrusion detection systems
   - Implement security event logging
   - Create real-time threat monitoring
   - Build security incident response tools
   - Implement security metrics and reporting

### Implementation Details
1. **Security Manager Interface**
   ```php
   interface SecurityManagerInterface
   {
       public function validateInput(string $input, string $type): bool;
       public function sanitizeInput(string $input, string $type): string;
       public function encryptData(string $data): string;
       public function decryptData(string $encryptedData): string;
       public function logSecurityEvent(string $event, array $details): void;
   }
   ```

2. **Access Control Interface**
   ```php
   interface AccessControlInterface
   {
       public function checkPermission(string $user, string $permission): bool;
       public function enforcePermission(string $user, string $permission): void;
       public function hasRole(string $user, string $role): bool;
   }
   ```

## Project Structure
```
challenge-99-security-hardening/
├── backend-php/
│   ├── src/
│   │   ├── Security/
│   │   │   ├── SecurityManager.php
│   │   │   ├── SecurityManagerInterface.php
│   │   │   ├── InputValidator.php
│   │   │   ├── DataSanitizer.php
│   │   │   └── Exception/
│   │   │       ├── SecurityException.php
│   │   │       └── AuthenticationException.php
│   │   ├── Authentication/
│   │   │   ├── Authenticator.php
│   │   │   ├── SessionManager.php
│   │   │   ├── MFAProvider.php
│   │   │   └── TokenManager.php
│   │   ├── Authorization/
│   │   │   ├── AccessControl.php
│   │   │   ├── RBACManager.php
│   │   │   ├── ABACManager.php
│   │   │   └── PolicyEnforcer.php
│   │   ├── Encryption/
│   │   │   ├── EncryptionManager.php
│   │   │   ├── KeyManager.php
│   │   │   ├── SecureStorage.php
│   │   │   └── DataMasker.php
│   │   └── Monitoring/
│   │       ├── IntrusionDetector.php
│   │       ├── SecurityLogger.php
│   │       ├── ThreatAnalyzer.php
│   │       └── AuditTrail.php
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
│   │   │   ├── SecurityDashboard.jsx
│   │   │   ├── AccessControl.jsx
│   │   │   ├── AuditTrail.jsx
│   │   │   └── ThreatMonitor.jsx
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
- OpenSSL for encryption

### Backend Setup
1. Navigate to the [backend-php](file:///c%3A/projects/php-fullstack-challenges/challenge-99-security-hardening/backend-php) directory
2. Install PHP dependencies:
   ```bash
   composer install
   ```
3. Configure security settings (php.ini):
   ```ini
   expose_php = Off
   display_errors = Off
   log_errors = On
   session.cookie_httponly = 1
   session.cookie_secure = 1
   session.use_strict_mode = 1
   ```
4. Start the development server:
   ```bash
   php public/index.php
   ```

### Frontend Setup
1. Navigate to the [frontend-react](file:///c%3A/projects/php-fullstack-challenges/challenge-99-security-hardening/frontend-react) directory
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

### Security Tools
- **POST** `/security/validate` - Validate and sanitize input
- **POST** `/security/encrypt` - Encrypt sensitive data
- **POST** `/security/decrypt` - Decrypt data
- **GET** `/security/events` - Get security events
- **POST** `/security/check-permission` - Check user permissions
- **GET** `/security/audit` - Get audit trail

## Implementation Details

### Core Security System

1. **Security Manager**
   ```php
   class SecurityManager implements SecurityManagerInterface
   {
       private InputValidator $validator;
       private DataSanitizer $sanitizer;
       private EncryptionManager $encryption;
       private SecurityLogger $logger;
       
       public function __construct(
           InputValidator $validator,
           DataSanitizer $sanitizer,
           EncryptionManager $encryption,
           SecurityLogger $logger
       ) {
           $this->validator = $validator;
           $this->sanitizer = $sanitizer;
           $this->encryption = $encryption;
           $this->logger = $logger;
       }
       
       public function validateInput(string $input, string $type): bool
       {
           $isValid = $this->validator->validate($input, $type);
           
           if (!$isValid) {
               $this->logger->log('INPUT_VALIDATION_FAILED', [
                   'input' => $input,
                   'type' => $type,
                   'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
               ]);
           }
           
           return $isValid;
       }
       
       public function sanitizeInput(string $input, string $type): string
       {
           return $this->sanitizer->sanitize($input, $type);
       }
       
       public function encryptData(string $data): string
       {
           return $this->encryption->encrypt($data);
       }
       
       public function decryptData(string $encryptedData): string
       {
           return $this->encryption->decrypt($encryptedData);
       }
       
       public function logSecurityEvent(string $event, array $details): void
       {
           $this->logger->log($event, $details);
       }
   }
   ```

2. **Input Validator**
   ```php
   class InputValidator
   {
       private array $rules = [];
       
       public function __construct()
       {
           $this->rules = [
               'email' => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
               'phone' => '/^\+?[1-9]\d{1,14}$/',
               'url' => '/^https?:\/\/(?:[-\w.])+(?:\:[0-9]+)?(?:\/(?:[\w\/_.])*(?:\?(?:[\w&=%.])*)?(?:\#(?:[\w.])*)?)?$/',
               'alphanumeric' => '/^[a-zA-Z0-9]+$/',
               'numeric' => '/^[0-9]+$/',
               'alpha' => '/^[a-zA-Z]+$/',
           ];
       }
       
       public function validate(string $input, string $type): bool
       {
           // Check for dangerous patterns
           $dangerousPatterns = [
               '/<script[^>]*>.*?<\/script>/is', // XSS
               '/(union|select|insert|update|delete|drop|create|alter|exec|execute)/i', // SQL Injection
               '/(\.\.\/|\.\.\\|%00)/i', // Path traversal
               '/(javascript:|vbscript:|data:)/i', // Dangerous protocols
           ];
           
           foreach ($dangerousPatterns as $pattern) {
               if (preg_match($pattern, $input)) {
                   return false;
               }
           }
           
           // Apply specific validation rules
           if (isset($this->rules[$type])) {
               return (bool) preg_match($this->rules[$type], $input);
           }
           
           // Default validation - check for control characters
           return !preg_match('/[\x00-\x1f\x7f]/', $input);
       }
       
       public function addRule(string $type, string $pattern): void
       {
           $this->rules[$type] = $pattern;
       }
   }
   ```

3. **Data Sanitizer**
   ```php
   class DataSanitizer
   {
       public function sanitize(string $input, string $type): string
       {
           switch ($type) {
               case 'html':
                   return htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
               
               case 'url':
                   return filter_var($input, FILTER_SANITIZE_URL);
               
               case 'email':
                   return filter_var($input, FILTER_SANITIZE_EMAIL);
               
               case 'string':
                   // Remove control characters and encode HTML entities
                   $clean = preg_replace('/[\x00-\x1f\x7f]/', '', $input);
                   return htmlspecialchars($clean, ENT_QUOTES | ENT_HTML5, 'UTF-8');
               
               case 'filename':
                   // Remove dangerous characters for filenames
                   return preg_replace('/[^a-zA-Z0-9._-]/', '', $input);
               
               default:
                   // Default sanitization
                   $clean = preg_replace('/[\x00-\x1f\x7f]/', '', $input);
                   return htmlspecialchars($clean, ENT_QUOTES | ENT_HTML5, 'UTF-8');
           }
       }
       
       public function sanitizeArray(array $data): array
       {
           $sanitized = [];
           foreach ($data as $key => $value) {
               if (is_string($value)) {
                   $sanitized[$key] = $this->sanitize($value, 'string');
               } elseif (is_array($value)) {
                   $sanitized[$key] = $this->sanitizeArray($value);
               } else {
                   $sanitized[$key] = $value;
               }
           }
           return $sanitized;
       }
   }
   ```

4. **Encryption Manager**
   ```php
   class EncryptionManager
   {
       private string $key;
       private string $cipher = 'AES-256-GCM';
       
       public function __construct(string $key = null)
       {
           $this->key = $key ?? $this->generateKey();
       }
       
       public function encrypt(string $data): string
       {
           $iv = random_bytes(openssl_cipher_iv_length($this->cipher));
           $tag = '';
           $encrypted = openssl_encrypt($data, $this->cipher, $this->key, OPENSSL_RAW_DATA, $iv, $tag);
           
           if ($encrypted === false) {
               throw new SecurityException('Encryption failed');
           }
           
           // Combine IV, tag, and encrypted data
           $result = base64_encode($iv . $tag . $encrypted);
           return $result;
       }
       
       public function decrypt(string $encryptedData): string
       {
           $data = base64_decode($encryptedData);
           $ivLength = openssl_cipher_iv_length($this->cipher);
           $tagLength = 16; // GCM tag is 16 bytes
           
           $iv = substr($data, 0, $ivLength);
           $tag = substr($data, $ivLength, $tagLength);
           $encrypted = substr($data, $ivLength + $tagLength);
           
           $decrypted = openssl_decrypt($encrypted, $this->cipher, $this->key, OPENSSL_RAW_DATA, $iv, $tag);
           
           if ($decrypted === false) {
               throw new SecurityException('Decryption failed');
           }
           
           return $decrypted;
       }
       
       public function hash(string $data, string $algorithm = 'sha256'): string
       {
           return hash($algorithm, $data);
       }
       
       public function hashPassword(string $password): string
       {
           return password_hash($password, PASSWORD_ARGON2ID, [
               'memory_cost' => 65536, // 64 MB
               'time_cost' => 4,       // 4 iterations
               'threads' => 3,         // 3 threads
           ]);
       }
       
       public function verifyPassword(string $password, string $hash): bool
       {
           return password_verify($password, $hash);
       }
       
       private function generateKey(): string
       {
           return random_bytes(32); // 256 bits
       }
   }
   ```

5. **Security Logger**
   ```php
   class SecurityLogger
   {
       private string $logFile;
       private int $maxLogSize = 10485760; // 10MB
       
       public function __construct(string $logFile = 'security.log')
       {
           $this->logFile = $logFile;
       }
       
       public function log(string $event, array $details = []): void
       {
           $logEntry = [
               'timestamp' => date('c'),
               'event' => $event,
               'details' => $details,
               'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
               'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
               'request_uri' => $_SERVER['REQUEST_URI'] ?? 'unknown'
           ];
           
           $logLine = json_encode($logEntry) . "\n";
           
           // Rotate log if too large
           if (file_exists($this->logFile) && filesize($this->logFile) > $this->maxLogSize) {
               $this->rotateLog();
           }
           
           file_put_contents($this->logFile, $logLine, FILE_APPEND | LOCK_EX);
       }
       
       public function getLogs(int $limit = 100): array
       {
           if (!file_exists($this->logFile)) {
               return [];
           }
           
           $lines = file($this->logFile, FILE_IGNORE_NEW_LINES);
           $logs = [];
           
           // Get last $limit entries
           $start = max(0, count($lines) - $limit);
           for ($i = $start; $i < count($lines); $i++) {
               $log = json_decode($lines[$i], true);
               if ($log) {
                   $logs[] = $log;
               }
           }
           
           return array_reverse($logs);
       }
       
       private function rotateLog(): void
       {
           $timestamp = date('Y-m-d-H-i-s');
           rename($this->logFile, $this->logFile . '.' . $timestamp);
       }
   }
   ```

6. **Intrusion Detector**
   ```php
   class IntrusionDetector
   {
       private SecurityLogger $logger;
       private array $thresholds = [
           'failed_login' => 5,      // 5 failed logins
           'suspicious_request' => 10, // 10 suspicious requests
           'brute_force' => 20,      // 20 requests in short time
       ];
       private array $events = [];
       
       public function __construct(SecurityLogger $logger)
       {
           $this->logger = $logger;
       }
       
       public function recordEvent(string $eventType, string $identifier): void
       {
           $key = $eventType . ':' . $identifier;
           $now = time();
           
           if (!isset($this->events[$key])) {
               $this->events[$key] = [
                   'count' => 0,
                   'timestamps' => []
               ];
           }
           
           $this->events[$key]['count']++;
           $this->events[$key]['timestamps'][] = $now;
           
           // Keep only recent timestamps (last hour)
           $this->events[$key]['timestamps'] = array_filter(
               $this->events[$key]['timestamps'],
               function($timestamp) use ($now) {
                   return ($now - $timestamp) < 3600;
               }
           );
           
           // Check thresholds
           $this->checkThresholds($eventType, $identifier, $key);
       }
       
       private function checkThresholds(string $eventType, string $identifier, string $key): void
       {
           $threshold = $this->thresholds[$eventType] ?? 100;
           $count = $this->events[$key]['count'];
           
           if ($count >= $threshold) {
               $this->triggerAlert($eventType, $identifier, $count);
           }
           
           // Check for brute force (many requests in short time)
           if ($eventType === 'request') {
               $recentCount = count($this->events[$key]['timestamps']);
               if ($recentCount >= $this->thresholds['brute_force']) {
                   $this->triggerAlert('brute_force', $identifier, $recentCount);
               }
           }
       }
       
       private function triggerAlert(string $eventType, string $identifier, int $count): void
       {
           $alert = [
               'type' => 'SECURITY_ALERT',
               'event_type' => $eventType,
               'identifier' => $identifier,
               'count' => $count,
               'severity' => $this->getSeverity($eventType)
           ];
           
           $this->logger->log('SECURITY_ALERT', $alert);
           
           // In a real implementation, you might:
           // - Send email alerts
           // - Block the IP
           // - Notify security team
           // - Trigger incident response
       }
       
       private function getSeverity(string $eventType): string
       {
           $severities = [
               'failed_login' => 'HIGH',
               'brute_force' => 'CRITICAL',
               'suspicious_request' => 'MEDIUM',
               'sql_injection' => 'CRITICAL',
               'xss' => 'HIGH'
           ];
           
           return $severities[$eventType] ?? 'LOW';
       }
   }
   ```

7. **Access Control Manager**
   ```php
   class AccessControlManager implements AccessControlInterface
   {
       private array $roles = [];
       private array $permissions = [];
       private array $userRoles = [];
       private array $rolePermissions = [];
       
       public function addUser(string $user, array $roles): void
       {
           $this->userRoles[$user] = $roles;
       }
       
       public function addRole(string $role, array $permissions): void
       {
           $this->rolePermissions[$role] = $permissions;
       }
       
       public function checkPermission(string $user, string $permission): bool
       {
           if (!isset($this->userRoles[$user])) {
               return false;
           }
           
           $userRoles = $this->userRoles[$user];
           
           foreach ($userRoles as $role) {
               if (isset($this->rolePermissions[$role])) {
                   if (in_array($permission, $this->rolePermissions[$role])) {
                       return true;
                   }
               }
           }
           
           return false;
       }
       
       public function enforcePermission(string $user, string $permission): void
       {
           if (!$this->checkPermission($user, $permission)) {
               throw new AuthorizationException("Access denied: User {$user} does not have permission {$permission}");
           }
       }
       
       public function hasRole(string $user, string $role): bool
       {
           return isset($this->userRoles[$user]) && in_array($role, $this->userRoles[$user]);
       }
       
       public function getPermissionsForUser(string $user): array
       {
           if (!isset($this->userRoles[$user])) {
               return [];
           }
           
           $permissions = [];
           foreach ($this->userRoles[$user] as $role) {
               if (isset($this->rolePermissions[$role])) {
                   $permissions = array_merge($permissions, $this->rolePermissions[$role]);
               }
           }
           
           return array_unique($permissions);
       }
   }
   ```

### Frontend Interface
The React frontend should:
1. Display security dashboard with real-time alerts
2. Show access control management interface
3. Visualize audit trails and security events
4. Provide security configuration tools
5. Offer educational content about security best practices
6. Include threat modeling and risk assessment tools

## Evaluation Criteria
1. **Security Effectiveness** (30%)
   - Proper implementation of security measures
   - Effective threat detection and prevention
   - Secure data handling and encryption

2. **Comprehensiveness** (25%)
   - Complete security coverage
   - Multiple security layers
   - Proper security monitoring

3. **Usability** (20%)
   - Intuitive security management
   - Clear security reporting
   - Easy integration with applications

4. **Performance** (15%)
   - Minimal security overhead
   - Efficient security checks
   - Proper resource management

5. **Documentation** (10%)
   - Clear security guidelines
   - Proper usage documentation
   - Security best practices

## Resources
1. [OWASP Top 10](https://owasp.org/www-project-top-ten/)
2. [PHP Security Best Practices](https://www.php.net/manual/en/security.php)
3. [NIST Cybersecurity Framework](https://www.nist.gov/cyberframework)
4. [CIS Controls](https://www.cisecurity.org/cis-controls/)
5. [ISO 27001](https://www.iso.org/isoiec-27001-information-security.html)
6. [Security Headers](https://securityheaders.com/)
7. [Mozilla Observatory](https://observatory.mozilla.org/)

## Stretch Goals
1. Implement zero-trust architecture patterns
2. Create advanced threat intelligence integration
3. Build machine learning-based anomaly detection
4. Implement blockchain-based audit trails
5. Create quantum-resistant cryptography support
6. Develop advanced penetration testing tools
7. Implement security orchestration and automation