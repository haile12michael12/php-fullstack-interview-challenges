<?php

declare(strict_types=1);

// Bootstrap file for the Markdown Previewer application

// Define base paths
define('ROOT_PATH', __DIR__ . '/..');
define('APP_PATH', __DIR__);
define('CONFIG_PATH', APP_PATH . '/Config');

// Load configuration
$config = require CONFIG_PATH . '/app.php';
$securityConfig = require CONFIG_PATH . '/security.php';
$exportConfig = require CONFIG_PATH . '/export.php';

// Set timezone
date_default_timezone_set($config['timezone'] ?? 'UTC');

// Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Apply security measures
\App\Infrastructure\Security\ContentSecurityPolicy::apply();

// Simple dependency container
class Container
{
    private array $instances = [];

    public function get(string $class)
    {
        if (!isset($this->instances[$class])) {
            $this->instances[$class] = new $class();
        }
        return $this->instances[$class];
    }
}

// Create and return container
return new Container();