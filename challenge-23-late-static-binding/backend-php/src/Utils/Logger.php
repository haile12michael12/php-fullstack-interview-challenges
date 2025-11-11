<?php

namespace App\Utils;

class Logger
{
    protected static $instance = null;
    protected $logFile;

    protected function __construct($logFile = null)
    {
        $this->logFile = $logFile ?: __DIR__ . '/../../storage/logs/app.log';
    }

    public static function getInstance($logFile = null)
    {
        if (static::$instance === null) {
            static::$instance = new static($logFile);
        }
        return static::$instance;
    }

    public static function info($message)
    {
        static::getInstance()->log('INFO', $message);
    }

    public static function error($message)
    {
        static::getInstance()->log('ERROR', $message);
    }

    public static function debug($message)
    {
        static::getInstance()->log('DEBUG', $message);
    }

    protected function log($level, $message)
    {
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[{$timestamp}] {$level}: {$message}" . PHP_EOL;
        
        file_put_contents($this->logFile, $logMessage, FILE_APPEND | LOCK_EX);
    }

    public function getLogFile()
    {
        return $this->logFile;
    }
}