<?php

namespace App\Core;

class View
{
    private string $view;
    private array $data;

    public function __construct(string $view, array $data = [])
    {
        $this->view = $view;
        $this->data = $data;
    }

    public static function make(string $view, array $data = []): self
    {
        return new self($view, $data);
    }

    public function render(): string
    {
        $viewPath = __DIR__ . "/../../resources/views/{$this->view}.php";
        
        if (!file_exists($viewPath)) {
            throw new \Exception("View file not found: {$viewPath}");
        }
        
        // Extract data to variables
        extract($this->data);
        
        // Start output buffering
        ob_start();
        
        // Include the view
        include $viewPath;
        
        // Get the content and clean the buffer
        return ob_get_clean();
    }

    public function with(array $data): self
    {
        $this->data = array_merge($this->data, $data);
        return $this;
    }

    public function __toString(): string
    {
        return $this->render();
    }
}