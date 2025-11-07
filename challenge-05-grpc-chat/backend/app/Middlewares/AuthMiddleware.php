<?php

namespace App\Middlewares;

class AuthMiddleware
{
    public function handle(): bool
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            // Redirect to login page
            header('Location: /login');
            exit;
            return false;
        }
        
        // User is authenticated, continue with the request
        return true;
    }
}