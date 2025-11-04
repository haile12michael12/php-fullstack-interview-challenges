<?php

namespace App;

use DI\ContainerBuilder;
use SharedBackend\Core\Config;
use SharedBackend\Core\Logger;
use App\Infrastructure\Request;
use App\Infrastructure\Response;
use App\Presentation\ControllerResolver;

class Application
{
    private $container;
    private $config;
    private $logger;
    
    public function __construct()
    {
        // Initialize container
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->addDefinitions(__DIR__ . '/../config/container.php');
        $this->container = $containerBuilder->build();
        
        // Load configuration
        $this->config = $this->container->get(Config::class);
        
        // Initialize logger
        $this->logger = $this->container->get(Logger::class);
    }
    
    /**
     * Run the application
     */
    public function run(): void
    {
        try {
            // Create request from globals
            $request = Request::createFromGlobals();
            
            // Resolve controller and action
            $resolver = $this->container->get(ControllerResolver::class);
            $controllerData = $resolver->resolve($request);
            
            if (!$controllerData) {
                $this->sendNotFound();
                return;
            }
            
            // Execute controller action
            $controller = $this->container->get($controllerData['controller']);
            $action = $controllerData['action'];
            $response = $controller->$action($request);
            
            // Send response
            $this->sendResponse($response);
            
        } catch (\Throwable $e) {
            $this->logger->error('Application error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            
            $this->sendError($e);
        }
    }
    
    /**
     * Send a 404 Not Found response
     */
    private function sendNotFound(): void
    {
        $response = new Response([
            'error' => 'Not Found',
            'message' => 'The requested resource was not found'
        ], 404);
        
        $this->sendResponse($response);
    }
    
    /**
     * Send an error response
     */
    private function sendError(\Throwable $e): void
    {
        $statusCode = 500;
        $data = [
            'error' => 'Internal Server Error',
            'message' => 'An unexpected error occurred'
        ];
        
        // In development, include more details
        if ($this->config->get('app.debug', false)) {
            $data['exception'] = get_class($e);
            $data['message'] = $e->getMessage();
            $data['file'] = $e->getFile();
            $data['line'] = $e->getLine();
            $data['trace'] = explode("\n", $e->getTraceAsString());
        }
        
        $response = new Response($data, $statusCode);
        $this->sendResponse($response);
    }
    
    /**
     * Send a response to the client
     */
    private function sendResponse(Response $response): void
    {
        // Set status code
        http_response_code($response->getStatusCode());
        
        // Set headers
        foreach ($response->getHeaders() as $name => $value) {
            header("$name: $value");
        }
        
        // Send body
        echo $response->getBody();
    }
}