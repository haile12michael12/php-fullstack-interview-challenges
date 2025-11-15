<?php

namespace Core;

class Logger
{
    private static $instance = null;
    private $logFile;

    private function __construct()
    {
        $this->logFile = __DIR__ . '/../../logs/app.log';
        // Create logs directory if it doesn't exist
        if (!is_dir(dirname($this->logFile))) {
            mkdir(dirname($this->logFile), 0755, true);
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }

    public function info($message)
    {
        $this->log('INFO', $message);
    }

    public function error($message)
    {
        $this->log('ERROR', $message);
    }

    public function debug($message)
    {
        $this->log('DEBUG', $message);
    }

    private function log($level, $message)
    {
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[{$timestamp}] {$level}: {$message}" . PHP_EOL;
        file_put_contents($this->logFile, $logMessage, FILE_APPEND | LOCK_EX);
    }
}