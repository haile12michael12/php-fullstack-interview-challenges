<?php

namespace App\Handler;

use App\Logger\LoggerFactory;
use Psr\Log\LoggerInterface;

class ShutdownHandler
{
    private LoggerInterface $logger;

    public function __construct()
    {
        $this->logger = LoggerFactory::create('shutdown');
    }

    public function register()
    {
        register_shutdown_function([$this, 'handle']);
    }

    public function handle()
    {
        $error = error_get_last();
        
        if ($error === null) {
            // Normal shutdown
            $this->logger->info('Application shutdown normally');
            return;
        }

        // Log the fatal error
        $this->logger->critical('Application shutdown due to fatal error', [
            'type' => $error['type'],
            'message' => $error['message'],
            'file' => $error['file'],
            'line' => $error['line'],
            'timestamp' => date('c')
        ]);

        // Try to send a clean error response
        if (!headers_sent()) {
            http_response_code(500);
            header('Content-Type: application/json');
            
            echo json_encode([
                'error' => 'Application shutdown due to fatal error',
                'message' => 'An unexpected error occurred. Please try again later.'
            ]);
        }
    }
}