<?php

use SharedBackend\Core\Config;
use SharedBackend\Core\Logger;
use SharedBackend\Auth\AuthManager;
use SharedBackend\Auth\TokenManager;
use SharedBackend\Auth\UserProviderInterface;
use App\Controller\AuthController;
use App\Controller\UserController;
use App\Controller\ProfileController;
use App\Controller\ControllerResolver;

return [
    // Core services
    Config::class => function() {
        return new Config(__DIR__ . '/..');
    },
    
    Logger::class => function($container) {
        $config = $container->get(Config::class);
        return new Logger($config);
    },
    
    // Auth services
    TokenManager::class => function($container) {
        $config = $container->get(Config::class);
        return new TokenManager($config);
    },
    
    AuthManager::class => function($container) {
        $config = $container->get(Config::class);
        $tokenManager = $container->get(TokenManager::class);
        // We'll use a simple implementation of the user provider interface for this example
        $userProvider = new class implements UserProviderInterface {
            public function getUserByUsername(string $username): ?array {
                return null;
            }
            public function getUserById($id): ?array {
                return null;
            }
        };
        return new AuthManager($config, $tokenManager, $userProvider);
    },
    
    // Controllers
    AuthController::class => function($container) {
        $authManager = $container->get(AuthManager::class);
        return new AuthController($authManager);
    },
    
    UserController::class => function($container) {
        return new UserController();
    },
    
    ProfileController::class => function($container) {
        return new ProfileController();
    },
    
    ControllerResolver::class => function($container) {
        $config = $container->get(Config::class);
        return new ControllerResolver($config);
    },
];