<?php

namespace App\Router;

class Router
{
    private array $routes = [];
    
    public function addRoute(string $method, string $pattern, callable $handler): void
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'pattern' => $pattern,
            'handler' => $handler
        ];
    }
    
    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            
            // Convert route pattern to regex
            $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $route['pattern']);
            $pattern = '#^' . $pattern . '$#';
            
            if (preg_match($pattern, $uri, $matches)) {
                // Remove the full match
                array_shift($matches);
                
                // Extract parameter names
                preg_match_all('/\{([^}]+)\}/', $route['pattern'], $paramNames);
                $paramNames = $paramNames[1];
                
                // Create associative array of parameters
                $params = [];
                for ($i = 0; $i < count($paramNames); $i++) {
                    $params[$paramNames[$i]] = $matches[$i] ?? null;
                }
                
                // Call the handler
                $response = call_user_func($route['handler'], $params);
                echo $response;
                return;
            }
        }
        
        // No route matched
        http_response_code(404);
        echo json_encode(['error' => 'Route not found']);
    }
    
    public function get(string $pattern, callable $handler): void
    {
        $this->addRoute('GET', $pattern, $handler);
    }
    
    public function post(string $pattern, callable $handler): void
    {
        $this->addRoute('POST', $pattern, $handler);
    }
    
    public function put(string $pattern, callable $handler): void
    {
        $this->addRoute('PUT', $pattern, $handler);
    }
    
    public function delete(string $pattern, callable $handler): void
    {
        $this->addRoute('DELETE', $pattern, $handler);
    }
}