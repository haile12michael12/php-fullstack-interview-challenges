<?php

namespace App\Infrastructure\Services;

class LoggingService
{
    private string $logPath;

    public function __construct(string $logPath = __DIR__ . '/../../../logs/app.log')
    {
        $this->logPath = $logPath;
        $this->ensureDirectoryExists(dirname($this->logPath));
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

    private function log(string $level, string $message, array $context = []): void
    {
        $timestamp = date('Y-m-d H:i:s');
        $contextString = !empty($context) ? json_encode($context) : '';
        $logMessage = "[{$timestamp}] {$level}: {$message} {$contextString}" . PHP_EOL;

        file_put_contents($this->logPath, $logMessage, FILE_APPEND | LOCK_EX);
    }

    private function ensureDirectoryExists(string $directory): void
    {
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
    }
}