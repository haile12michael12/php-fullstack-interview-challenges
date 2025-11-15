<?php

namespace Services;

use Core\Config;
use Core\Logger;

class AuthService
{
    private static $instance = null;
    private $config;

    private function __construct()
    {
        $this->config = Config::getInstance();
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }

    public function authenticate(string $username, string $password): bool
    {
        // In a real application, this would check against a database
        // For this example, we'll use environment variables
        $validUsername = $this->config->get('ADMIN_USERNAME', 'admin');
        $validPassword = $this->config->get('ADMIN_PASSWORD', 'password');
        
        $isAuthenticated = ($username === $validUsername && $password === $validPassword);
        
        if ($isAuthenticated) {
            Logger::getInstance()->info("User {$username} authenticated successfully");
        } else {
            Logger::getInstance()->info("Authentication failed for user {$username}");
        }
        
        return $isAuthenticated;
    }

    public function generateToken(string $username): string
    {
        // In a real application, this would generate a proper JWT or session token
        // For this example, we'll create a simple token
        $token = base64_encode($username . ':' . time() . ':' . uniqid());
        Logger::getInstance()->info("Token generated for user {$username}");
        return $token;
    }
}