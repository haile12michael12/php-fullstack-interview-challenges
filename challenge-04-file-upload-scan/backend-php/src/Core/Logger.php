<?php

namespace Challenge04\Core;

class Logger
{
    private string $logFile;
    private string $logLevel;

    public function __construct(string $logFile = '/tmp/app.log', string $logLevel = 'INFO')
    {
        $this->logFile = $logFile;
        $this->logLevel = $logLevel;
    }

    public function info(string $message, array $context = []): void
    {
        $this->log('INFO', $message, $context);
    }

    public function error(string $message, array $context = []): void
    {
        $this->log('ERROR', $message, $context);
    }

    public function warning(string $message, array $context = []): void
    {
        $this->log('WARNING', $message, $context);
    }

    public function debug(string $message, array $context = []): void
    {
        $this->log('DEBUG', $message, $context);
    }

    private function log(string $level, string $message, array $context = []): void
    {
        // Check if we should log this level
        if (!$this->shouldLog($level)) {
            return;
        }
        
        $timestamp = date('Y-m-d H:i:s');
        $contextString = !empty($context) ? json_encode($context) : '';
        $logEntry = "[{$timestamp}] {$level}: {$message} {$contextString}" . PHP_EOL;
        
        file_put_contents($this->logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }

    private function shouldLog(string $level): bool
    {
        $levels = ['DEBUG' => 0, 'INFO' => 1, 'WARNING' => 2, 'ERROR' => 3];
        
        if (!isset($levels[$level]) || !isset($levels[$this->logLevel])) {
            return true; // Log if we can't determine levels
        }
        
        return $levels[$level] >= $levels[$this->logLevel];
    }

    public function getLogFile(): string
    {
        return $this->logFile;
    }

    public function setLogLevel(string $logLevel): void
    {
        $this->logLevel = $logLevel;
    }
}