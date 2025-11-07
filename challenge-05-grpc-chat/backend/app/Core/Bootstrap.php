<?php

namespace App\Core;

class Bootstrap
{
    private Container $container;
    private Router $router;

    public function __construct()
    {
        $this->container = new Container();
        $this->router = new Router();
        $this->initialize();
    }

    private function initialize(): void
    {
        // Load configuration
        $this->loadConfiguration();
        
        // Register services
        $this->registerServices();
        
        // Load routes
        $this->loadRoutes();
    }

    private function loadConfiguration(): void
    {
        // Load configuration files
        $configFiles = [
            __DIR__ . '/../../config/app.php',
            __DIR__ . '/../../config/database.php',
            __DIR__ . '/../../config/services.php'
        ];
        
        foreach ($configFiles as $file) {
            if (file_exists($file)) {
                $config = require $file;
                // Process configuration
            }
        }
    }

    private function registerServices(): void
    {
        // Register core services in the container
        $this->container->register('router', $this->router);
    }

    private function loadRoutes(): void
    {
        $routeFile = __DIR__ . '/../../routes/web.php';
        if (file_exists($routeFile)) {
            require $routeFile;
        }
    }

    public function getContainer(): Container
    {
        return $this->container;
    }

    public function getRouter(): Router
    {
        return $this->router;
    }

    public function run(): void
    {
        // Get the current request URI
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        
        // Dispatch the request
        $this->router->dispatch($uri);
    }
}