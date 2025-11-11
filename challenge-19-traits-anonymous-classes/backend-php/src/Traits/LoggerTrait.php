<?php

namespace App\Traits;

trait LoggerTrait
{
    protected $logLevel = 'info';
    protected $logFile = 'app.log';

    public function setLogLevel(string $level): void
    {
        $this->logLevel = $level;
    }

    public function setLogFile(string $file): void
    {
        $this->logFile = $file;
    }

    public function log(string $message, string $level = 'info'): void
    {
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[{$timestamp}] [{$level}] {$message}" . PHP_EOL;
        
        file_put_contents($this->logFile, $logMessage, FILE_APPEND | LOCK_EX);
    }

    public function info(string $message): void
    {
        $this->log($message, 'info');
    }

    public function error(string $message): void
    {
        $this->log($message, 'error');
    }

    public function debug(string $message): void
    {
        $this->log($message, 'debug');
    }
}