<?php

namespace App\Middlewares;

class CsrfMiddleware
{
    public function handle(): bool
    {
        // For POST, PUT, DELETE requests, verify CSRF token
        if (in_array($_SERVER['REQUEST_METHOD'], ['POST', 'PUT', 'DELETE'])) {
            $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
            
            if (!$this->verifyToken($token)) {
                http_response_code(403);
                echo json_encode(['error' => 'Invalid CSRF token']);
                return false;
            }
        }
        
        // Add CSRF token to the response for forms
        $this->addCsrfToken();
        
        return true;
    }

    private function verifyToken(string $token): bool
    {
        // In a real implementation, you would check against a stored token
        // For now, we'll just check if it's not empty
        return !empty($token);
    }

    private function addCsrfToken(): void
    {
        // In a real implementation, you would generate and store a CSRF token
        // For now, we'll just set a placeholder
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }

    public function getCsrfToken(): string
    {
        return $_SESSION['csrf_token'] ?? '';
    }
}