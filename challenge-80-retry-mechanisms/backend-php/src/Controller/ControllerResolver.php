<?php

namespace App\Controller;

use SharedBackend\Core\Config;

class ControllerResolver
{
    private $config;
    private $routes;
    
    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->routes = $config->get('routes', []);
    }
    
    /**
     * Resolve a controller and action from a request
     * 
     * @param \App\Http\Request $request
     * @return array|null ['controller' => string, 'action' => string, 'params' => array]
     */
    public function resolve($request): ?array
    {
        $path = $request->getPath();
        $method = $request->getMethod();
        
        foreach ($this->routes as $route => $handlers) {
            // Skip if method doesn't match
            if (!isset($handlers[$method])) {
                continue;
            }
            
            $pattern = $this->convertRouteToPattern($route);
            if (preg_match($pattern, $path, $matches)) {
                // Extract route parameters
                $params = [];
                foreach ($matches as $key => $value) {
                    if (is_string($key)) {
                        $params[$key] = $value;
                    }
                }
                
                // Get controller and action
                $handler = $handlers[$method];
                [$controller, $action] = explode('@', $handler);
                
                return [
                    'controller' => $controller,
                    'action' => $action,
                    'params' => $params
                ];
            }
        }
        
        return null;
    }
    
    /**
     * Convert a route pattern to a regex pattern
     * 
     * @param string $route
     * @return string
     */
    private function convertRouteToPattern(string $route): string
    {
        // Replace {param} with named capture groups
        $pattern = preg_replace('/{([a-zA-Z0-9_]+)}/', '(?<$1>[^/]+)', $route);
        
        // Escape slashes and add start/end anchors
        return '#^' . $pattern . '$#';
    }
}