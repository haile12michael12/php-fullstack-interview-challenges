<?php

namespace App\Core;

class Router
{
    private array $routes = [];
    private array $middleware = [];

    public function get(string $uri, $callback, array $middleware = []): void
    {
        $this->addRoute('GET', $uri, $callback, $middleware);
    }

    public function post(string $uri, $callback, array $middleware = []): void
    {
        $this->addRoute('POST', $uri, $callback, $middleware);
    }

    public function put(string $uri, $callback, array $middleware = []): void
    {
        $this->addRoute('PUT', $uri, $callback, $middleware);
    }

    public function delete(string $uri, $callback, array $middleware = []): void
    {
        $this->addRoute('DELETE', $uri, $callback, $middleware);
    }

    private function addRoute(string $method, string $uri, $callback, array $middleware = []): void
    {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'callback' => $callback,
            'middleware' => $middleware
        ];
    }

    public function dispatch(string $uri): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['uri'] === $uri) {
                // Execute middleware
                foreach ($route['middleware'] as $mw) {
                    $middleware = new $mw();
                    if (!$middleware->handle()) {
                        return; // Middleware stopped the request
                    }
                }
                
                // Execute the route callback
                if (is_callable($route['callback'])) {
                    call_user_func($route['callback']);
                } elseif (is_string($route['callback'])) {
                    // Handle controller@method format
                    [$controller, $method] = explode('@', $route['callback']);
                    $controllerClass = "App\\Controllers\\{$controller}";
                    if (class_exists($controllerClass)) {
                        $controllerInstance = new $controllerClass();
                        if (method_exists($controllerInstance, $method)) {
                            $controllerInstance->$method();
                        }
                    }
                }
                return;
            }
        }
        
        // Route not found
        http_response_code(404);
        echo "404 - Page not found";
    }

    public function addMiddleware(string $middleware): void
    {
        $this->middleware[] = $middleware;
    }
}