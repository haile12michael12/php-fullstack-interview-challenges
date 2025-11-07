<?php

namespace App\Helpers;

class Logger
{
    private string $logFile;
    private string $logLevel;
    
    public function __construct(string $logFile = '', string $logLevel = 'INFO')
    {
        $this->logFile = $logFile ?: (__DIR__ . '/../../var/log/app.log');
        $this->logLevel = $logLevel;
    }
    
    public function emergency(string $message, array $context = []): void
    {
        $this->log('EMERGENCY', $message, $context);
    }
    
    public function alert(string $message, array $context = []): void
    {
        $this->log('ALERT', $message, $context);
    }
    
    public function critical(string $message, array $context = []): void
    {
        $this->log('CRITICAL', $message, $context);
    }
    
    public function error(string $message, array $context = []): void
    {
        $this->log('ERROR', $message, $context);
    }
    
    public function warning(string $message, array $context = []): void
    {
        $this->log('WARNING', $message, $context);
    }
    
    public function notice(string $message, array $context = []): void
    {
        $this->log('NOTICE', $message, $context);
    }
    
    public function info(string $message, array $context = []): void
    {
        $this->log('INFO', $message, $context);
    }
    
    public function debug(string $message, array $context = []): void
    {
        $this->log('DEBUG', $message, $context);
    }
    
    private function log(string $level, string $message, array $context = []): void
    {
        if (!$this->shouldLog($level)) {
            return;
        }
        
        $timestamp = date('Y-m-d H:i:s');
        $contextString = !empty($context) ? ' ' . json_encode($context) : '';
        $logMessage = "[{$timestamp}] {$level}: {$message}{$contextString}" . PHP_EOL;
        
        file_put_contents($this->logFile, $logMessage, FILE_APPEND | LOCK_EX);
    }
    
    private function shouldLog(string $level): bool
    {
        $levels = [
            'DEBUG' => 0,
            'INFO' => 1,
            'NOTICE' => 2,
            'WARNING' => 3,
            'ERROR' => 4,
            'CRITICAL' => 5,
            'ALERT' => 6,
            'EMERGENCY' => 7
        ];
        
        $currentLevel = $levels[$this->logLevel] ?? 1;
        $messageLevel = $levels[$level] ?? 1;
        
        return $messageLevel >= $currentLevel;
    }
}