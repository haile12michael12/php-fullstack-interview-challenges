<?php

namespace App\Logger;

use App\Logger\Logger;
use App\Logger\LogHandler;
use App\Storage\FileStorage;
use App\Logger\Formatter\JsonFormatter;

class LoggerFactory
{
    public static function createLogger(array $config = []): Logger
    {
        $logger = Logger::getInstance();
        
        // Create default handler if none exists
        if (empty($logger->getHandlers())) {
            $storage = new FileStorage($config['log_file'] ?? 'logs/app.log');
            $formatter = new JsonFormatter();
            $handler = new LogHandler($storage, $formatter);
            $logger->addHandler($handler);
        }
        
        return $logger;
    }
}