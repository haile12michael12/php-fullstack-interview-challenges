<?php

use SharedBackend\Core\Config;
use SharedBackend\Core\Logger;
use SharedBackend\Core\Database;
use SharedBackend\Cache\CacheManager;
use SharedBackend\Auth\TokenManager;
use SharedBackend\Storage\FileStorage;
use SharedBackend\Storage\StorageInterface;
use App\Presentation\ApiController;
use App\Presentation\ControllerResolver;

return [
    // Core services
    Config::class => function() {
        return new Config(__DIR__ . '/..');
    },
    
    Logger::class => function($container) {
        $config = $container->get(Config::class);
        return new Logger($config);
    },
    
    Database::class => function($container) {
        $config = $container->get(Config::class);
        $logger = $container->get(Logger::class);
        return new Database($config, $logger);
    },
    
    // Use CacheManager instead of Cache
    CacheManager::class => function($container) {
        $config = $container->get(Config::class);
        $logger = $container->get(Logger::class);
        return new CacheManager($config, $logger);
    },
    
    // Auth services
    TokenManager::class => function($container) {
        $config = $container->get(Config::class);
        return new TokenManager($config);
    },
    
    // Storage
    StorageInterface::class => function($container) {
        $config = $container->get(Config::class);
        return new FileStorage($config);
    },
    
    // Controllers
    ApiController::class => function($container) {
        return new ApiController();
    },
    
    ControllerResolver::class => function($container) {
        $config = $container->get(Config::class);
        return new ControllerResolver($config);
    },
];