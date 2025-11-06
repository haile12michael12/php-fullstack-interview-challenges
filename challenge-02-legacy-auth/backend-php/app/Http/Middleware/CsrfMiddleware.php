<?php

namespace Challenge02\Http\Middleware;

class CsrfMiddleware
{
    public function handle(): bool
    {
        // Check if CSRF token is present in the request
        $csrfToken = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? $_POST['csrf_token'] ?? '';
        
        // In a real implementation, you would validate the token against a stored value
        // For now, we'll just check if it's not empty
        if (empty($csrfToken)) {
            http_response_code(403);
            echo json_encode(['error' => 'CSRF token missing']);
            return false;
        }

        // CSRF token is present, continue with the request
        return true;
    }
}