<?php

namespace App\Logger;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Formatter\LineFormatter;
use Psr\Log\LoggerInterface;

class LoggerFactory
{
    private static array $loggers = [];

    public static function create(string $name, string $level = 'debug'): LoggerInterface
    {
        if (isset(self::$loggers[$name])) {
            return self::$loggers[$name];
        }

        $logger = new Logger($name);
        
        // Create formatter
        $formatter = new LineFormatter(
            "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n",
            null,
            true,
            true
        );

        // Add handlers based on environment
        if (getenv('APP_ENV') === 'production') {
            // In production, use rotating files
            $handler = new RotatingFileHandler(
                __DIR__ . '/../../storage/logs/' . $name . '.log',
                0,
                self::getMonologLevel($level)
            );
        } else {
            // In development, log to stdout
            $handler = new StreamHandler('php://stdout', self::getMonologLevel($level));
        }
        
        $handler->setFormatter($formatter);
        $logger->pushHandler($handler);

        self::$loggers[$name] = $logger;
        return $logger;
    }

    private static function getMonologLevel(string $level): int
    {
        $levels = [
            'debug' => Logger::DEBUG,
            'info' => Logger::INFO,
            'notice' => Logger::NOTICE,
            'warning' => Logger::WARNING,
            'error' => Logger::ERROR,
            'critical' => Logger::CRITICAL,
            'alert' => Logger::ALERT,
            'emergency' => Logger::EMERGENCY,
        ];

        return $levels[$level] ?? Logger::DEBUG;
    }
}