# Challenge 74: Configuration Management

## Description
In this challenge, you'll implement a comprehensive configuration management system that handles application settings across different environments. Proper configuration management is essential for deploying applications consistently across development, testing, and production environments.

## Learning Objectives
- Understand configuration management principles
- Implement configuration loading from multiple sources
- Handle environment-specific configurations
- Create configuration validation
- Manage sensitive configuration data
- Implement configuration reloading

## Requirements
Create a configuration management system with the following features:

1. **Configuration Sources**:
   - Environment variables
   - Configuration files (YAML, JSON, PHP)
   - Command-line arguments
   - Database storage
   - Remote configuration services
   - Default values

2. **Environment Management**:
   - Environment detection
   - Environment-specific overrides
   - Profile-based configuration
   - Configuration merging
   - Fallback mechanisms
   - Environment variable interpolation

3. **Security Features**:
   - Sensitive data encryption
   - Configuration access control
   - Audit logging
   - Secret management integration
   - Certificate-based configuration
   - Secure configuration distribution

4. **Advanced Features**:
   - Configuration validation
   - Type casting and conversion
   - Configuration caching
   - Hot reloading
   - Configuration versioning
   - Remote configuration synchronization

## Features to Implement
- [ ] Multiple configuration sources support
- [ ] Environment-specific configuration
- [ ] Configuration merging and overriding
- [ ] Sensitive data handling
- [ ] Configuration validation
- [ ] Type casting and conversion
- [ ] Configuration caching
- [ ] Hot reloading capability
- [ ] Remote configuration support
- [ ] Audit logging
- [ ] Access control

## Project Structure
```
backend-php/
├── src/
│   ├── Config/
│   │   ├── ConfigManager.php
│   │   ├── Loader/
│   │   │   ├── FileLoader.php
│   │   │   ├── EnvironmentLoader.php
│   │   │   ├── DatabaseLoader.php
│   │   │   └── RemoteLoader.php
│   │   ├── Parser/
│   │   │   ├── YamlParser.php
│   │   │   ├── JsonParser.php
│   │   │   └── PhpParser.php
│   │   ├── Validator/
│   │   │   ├── ConfigValidator.php
│   │   │   └── ValidationRule.php
│   │   ├── Security/
│   │   │   ├── EncryptionManager.php
│   │   │   ├── AccessControl.php
│   │   │   └── AuditLogger.php
│   │   └── Cache/
│   │       └── ConfigCache.php
│   ├── Http/
│   │   └── Request.php
│   └── Services/
├── public/
│   └── index.php
├── config/
│   ├── app.php
│   ├── database.php
│   ├── cache.php
│   ├── queue.php
│   └── environments/
│       ├── development.php
│       ├── testing.php
│       └── production.php
├── storage/
│   └── config/
├── vendor/
└── .env

frontend-react/
├── src/
│   ├── api/
│   │   └── config.js
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

## Configuration Loading Order
Configuration values are loaded in the following order (later sources override earlier ones):
1. Default values in code
2. Configuration files
3. Environment-specific files
4. Environment variables
5. Command-line arguments
6. Database configuration
7. Remote configuration

## Configuration File Examples

### app.php
```php
<?php
return [
    'name' => 'My Application',
    'debug' => false,
    'url' => 'http://localhost',
    'timezone' => 'UTC',
    'locale' => 'en',
    'fallback_locale' => 'en',
    'key' => env('APP_KEY'),
    'cipher' => 'AES-256-CBC',
];
```

### database.php
```php
<?php
return [
    'default' => env('DB_CONNECTION', 'mysql'),
    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
        ],
    ],
];
```

### Environment-specific configuration (.env)
```env
APP_NAME=MyApplication
APP_ENV=production
APP_KEY=base64:your-app-key-here
APP_DEBUG=false
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=myapp
DB_USERNAME=myuser
DB_PASSWORD=mypassword

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
```

## API Endpoints
```
# Configuration Management
GET    /config                    # Get all configuration
GET    /config/{key}             # Get specific configuration value
POST   /config                   # Update configuration
PUT    /config/{key}             # Update specific configuration
DELETE /config/{key}             # Delete configuration value
GET    /config/reload            # Reload configuration
GET    /config/validate          # Validate configuration
GET    /config/audit             # Get configuration audit log
```

## Configuration Validation Example
```php
$validationRules = [
    'app.name' => 'required|string|max:255',
    'app.debug' => 'boolean',
    'app.url' => 'required|url',
    'database.connections.mysql.host' => 'required|ip',
    'database.connections.mysql.port' => 'required|integer|between:1,65535',
    'cache.default' => 'required|in:apc,array,database,file,memcached,redis',
];

$validator = new ConfigValidator($validationRules);
if (!$validator->validate($config)) {
    throw new InvalidConfigurationException($validator->getErrors());
}
```

## Sensitive Data Handling
```php
// Encrypt sensitive configuration values
$encrypted = EncryptionManager::encrypt('my-secret-password');

// Store encrypted value in configuration
$config['database.password'] = $encrypted;

// Decrypt when needed
$decrypted = EncryptionManager::decrypt($config['database.password']);
```

## Remote Configuration Integration
```php
class RemoteConfigLoader implements ConfigLoaderInterface
{
    public function load(): array
    {
        try {
            $response = $this->httpClient->get('https://config-service.example.com/api/config');
            return json_decode($response->getBody(), true);
        } catch (Exception $e) {
            // Fallback to local configuration
            return [];
        }
    }
}
```

## Configuration Caching
```php
class ConfigCache
{
    public function get($key, $default = null)
    {
        if ($this->cache->has($key)) {
            return $this->cache->get($key);
        }
        
        $value = $this->loadFromSource($key);
        $this->cache->set($key, $value, 3600); // Cache for 1 hour
        return $value;
    }
    
    public function clear()
    {
        $this->cache->clear();
    }
}
```

## Evaluation Criteria
- [ ] Multiple configuration sources are supported
- [ ] Environment-specific configuration works
- [ ] Configuration merging and overriding function
- [ ] Sensitive data is handled securely
- [ ] Configuration validation is implemented
- [ ] Type casting and conversion work
- [ ] Configuration caching improves performance
- [ ] Hot reloading capability functions
- [ ] Remote configuration support is integrated
- [ ] Audit logging tracks changes
- [ ] Access control protects configuration
- [ ] Code is well-organized and documented
- [ ] Tests cover configuration functionality

## Resources
- [Configuration Management](https://microservices.io/patterns/externalized-configuration.html)
- [Twelve-Factor App - Config](https://12factor.net/config)
- [Spring Cloud Config](https://cloud.spring.io/spring-cloud-config/)
- [Consul Configuration](https://www.consul.io/docs/dynamic-configuration)
- [Vault Secrets Management](https://www.vaultproject.io/)