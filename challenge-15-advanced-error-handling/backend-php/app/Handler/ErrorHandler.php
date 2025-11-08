<?php

namespace App\Handler;

use App\Logger\LoggerFactory;
use ErrorException;
use Psr\Log\LoggerInterface;

class ErrorHandler
{
    private LoggerInterface $logger;

    public function __construct()
    {
        $this->logger = LoggerFactory::create('error');
        $this->register();
    }

    public function register()
    {
        // Convert PHP errors to exceptions
        set_error_handler([$this, 'handleError']);
        
        // Handle fatal errors
        register_shutdown_function([$this, 'handleShutdown']);
    }

    public function handleError(
        int $level,
        string $message,
        string $file = '',
        int $line = 0
    ) {
        if (!(error_reporting() & $level)) {
            // This error code is not included in error_reporting
            return;
        }

        $this->logger->error('PHP Error', [
            'level' => $level,
            'message' => $message,
            'file' => $file,
            'line' => $line
        ]);

        throw new ErrorException($message, 0, $level, $file, $line);
    }

    public function handleShutdown()
    {
        $error = error_get_last();
        
        if ($error !== null) {
            $this->logger->critical('Fatal Error', [
                'type' => $error['type'],
                'message' => $error['message'],
                'file' => $error['file'],
                'line' => $error['line']
            ]);
            
            // In production, you might want to show a friendly error page
            if (getenv('APP_ENV') !== 'production') {
                echo "Fatal error occurred: {$error['message']} in {$error['file']} on line {$error['line']}\n";
            }
        }
    }
}