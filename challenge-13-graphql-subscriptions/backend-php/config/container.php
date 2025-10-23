<?php

use SharedBackend\Core\Config;
use SharedBackend\Core\Logger;
use SharedBackend\Core\Database;
use SharedBackend\Core\Cache;
use SharedBackend\Auth\AuthManager;
use SharedBackend\Auth\TokenManager;
use SharedBackend\Storage\FileStorage;
use SharedBackend\Storage\StorageInterface;
use App\Repository\UserRepository;
use App\Service\UserService;
use App\Controller\ApiController;
use App\Controller\ControllerResolver;

return [
    // Core services
    Config::class => function() {
        $config = new Config();
        $config->load(__DIR__ . '/config.php');
        return $config;
    },
    
    Logger::class => function(\Psr\Container\ContainerInterface $container) {
        $config = $container->get(Config::class);
        return new Logger($config);
    },
    
    Database::class => function(\Psr\Container\ContainerInterface $container) {
        $config = $container->get(Config::class);
        return new Database($config);
    },
    
    Cache::class => function(\Psr\Container\ContainerInterface $container) {
        $config = $container->get(Config::class);
        return new Cache($config);
    },
    
    // Auth services
    TokenManager::class => function(\Psr\Container\ContainerInterface $container) {
        $config = $container->get(Config::class);
        return new TokenManager($config);
    },
    
    AuthManager::class => function(\Psr\Container\ContainerInterface $container) {
        $config = $container->get(Config::class);
        $tokenManager = $container->get(TokenManager::class);
        $userRepository = $container->get(UserRepository::class);
        return new AuthManager($config, $tokenManager, $userRepository);
    },
    
    // Storage
    StorageInterface::class => function(\Psr\Container\ContainerInterface $container) {
        $config = $container->get(Config::class);
        return new FileStorage($config);
    },
    
    // Repositories
    UserRepository::class => function(\Psr\Container\ContainerInterface $container) {
        $db = $container->get(Database::class);
        return new UserRepository($db);
    },
    
    // Services
    UserService::class => function(\Psr\Container\ContainerInterface $container) {
        $userRepository = $container->get(UserRepository::class);
        $authManager = $container->get(AuthManager::class);
        return new UserService($userRepository, $authManager);
    },
    
    // Controllers
    ApiController::class => function(\Psr\Container\ContainerInterface $container) {
        $userService = $container->get(UserService::class);
        return new ApiController($userService);
    },
    
    ControllerResolver::class => function(\Psr\Container\ContainerInterface $container) {
        $config = $container->get(Config::class);
        return new ControllerResolver($config);
    },
];