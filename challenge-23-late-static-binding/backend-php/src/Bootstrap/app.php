<?php

require_once __DIR__ . '/autoload.php';

use App\Utils\Env;
use App\Utils\Config;

// Load environment variables
Env::getInstance();

// Load configuration
Config::getInstance();

// Application class
class Application
{
    protected $router;
    
    public function __construct()
    {
        $this->router = new Router();
    }
    
    public function run()
    {
        // Load routes
        $this->loadRoutes();
        
        // Dispatch the request
        $this->router->dispatch();
    }
    
    protected function loadRoutes()
    {
        // Load API routes
        $apiRoutes = __DIR__ . '/../Http/Routes/api.php';
        if (file_exists($apiRoutes)) {
            require $apiRoutes;
        }
        
        // Load web routes
        $webRoutes = __DIR__ . '/../Http/Routes/web.php';
        if (file_exists($webRoutes)) {
            require $webRoutes;
        }
    }
}

// Simple router class
class Router
{
    protected $routes = [];
    
    public function get($pattern, $callback)
    {
        $this->addRoute('GET', $pattern, $callback);
    }
    
    public function post($pattern, $callback)
    {
        $this->addRoute('POST', $pattern, $callback);
    }
    
    public function put($pattern, $callback)
    {
        $this->addRoute('PUT', $pattern, $callback);
    }
    
    public function delete($pattern, $callback)
    {
        $this->addRoute('DELETE', $pattern, $callback);
    }
    
    protected function addRoute($method, $pattern, $callback)
    {
        $this->routes[] = [
            'method' => $method,
            'pattern' => $pattern,
            'callback' => $callback
        ];
    }
    
    public function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            
            // Convert route pattern to regex
            $pattern = preg_replace('/\{(\w+)\}/', '(\w+)', $route['pattern']);
            $pattern = '#^' . $pattern . '$#';
            
            if (preg_match($pattern, $uri, $matches)) {
                // Remove the full match
                array_shift($matches);
                
                // Call the callback with matches as parameters
                if (is_callable($route['callback'])) {
                    call_user_func_array($route['callback'], $matches);
                } elseif (is_array($route['callback']) && count($route['callback']) === 2) {
                    $controller = new $route['callback'][0];
                    $method = $route['callback'][1];
                    call_user_func_array([$controller, $method], $matches);
                }
                return;
            }
        }
        
        // No route found
        http_response_code(404);
        echo json_encode(['error' => 'Not Found']);
    }
}