<?php

namespace App\Service;

use App\Component\RequestHandlerInterface;

class PipelineBuilder
{
    private RequestHandlerInterface $finalHandler;
    private array $middlewareStack = [];
    
    public function __construct(RequestHandlerInterface $finalHandler)
    {
        $this->finalHandler = $finalHandler;
    }
    
    /**
     * Add middleware to the pipeline
     *
     * @param string $middlewareClass The middleware class to add
     * @param array $options Optional configuration for the middleware
     * @return self
     */
    public function addMiddleware(string $middlewareClass, array $options = []): self
    {
        $this->middlewareStack[] = [
            'class' => $middlewareClass,
            'options' => $options
        ];
        
        return $this;
    }
    
    /**
     * Build the middleware pipeline
     *
     * @return RequestHandlerInterface
     */
    public function build(): RequestHandlerInterface
    {
        $handler = $this->finalHandler;
        
        // Process middleware in reverse order (last in, first out)
        foreach (array_reverse($this->middlewareStack) as $middleware) {
            $middlewareClass = $middleware['class'];
            $options = $middleware['options'];
            
            if (!empty($options)) {
                $handler = new $middlewareClass($handler, ...array_values($options));
            } else {
                $handler = new $middlewareClass($handler);
            }
        }
        
        return $handler;
    }
    
    /**
     * Get the middleware stack
     *
     * @return array
     */
    public function getMiddlewareStack(): array
    {
        return $this->middlewareStack;
    }
}