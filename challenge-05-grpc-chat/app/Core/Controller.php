<?php

namespace App\Core;

abstract class Controller
{
    protected Container $container;

    public function __construct()
    {
        $this->container = new Container();
    }

    protected function render(string $view, array $data = []): void
    {
        $viewPath = __DIR__ . "/../../resources/views/{$view}.php";
        
        if (file_exists($viewPath)) {
            // Extract data to variables
            extract($data);
            
            // Start output buffering
            ob_start();
            
            // Include the view
            include $viewPath;
            
            // Get the content and clean the buffer
            $content = ob_get_clean();
            
            // Output the content
            echo $content;
        } else {
            throw new \Exception("View file not found: {$viewPath}");
        }
    }

    protected function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }

    protected function getService(string $serviceName)
    {
        return $this->container->get($serviceName);
    }

    protected function getRequestData(): array
    {
        $data = [];
        
        // Handle JSON requests
        if (strpos($_SERVER['CONTENT_TYPE'] ?? '', 'application/json') !== false) {
            $input = file_get_contents('php://input');
            $data = json_decode($input, true) ?? [];
        } else {
            // Handle form data
            $data = array_merge($_GET, $_POST);
        }
        
        return $data;
    }
}