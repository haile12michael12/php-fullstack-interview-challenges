<?php

// Bootstrap file - initializes the application

// Register autoloader
require_once __DIR__ . '/src/Core/Autoloader.php';
\Core\Autoloader::register();

// Load environment variables
require_once __DIR__ . '/src/Core/Env.php';

// Initialize configuration
require_once __DIR__ . '/src/Core/Config.php';
\Core\Config::getInstance();

// Initialize logger
require_once __DIR__ . '/src/Core/Logger.php';
\Core\Logger::getInstance();