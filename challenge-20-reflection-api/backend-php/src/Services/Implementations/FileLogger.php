<?php

namespace App\Services\Implementations;

use App\Services\Interfaces\LoggerInterface;

class FileLogger implements LoggerInterface
{
    private string $logFile;
    private string $dateFormat;

    public function __construct(string $logFile = 'app.log', string $dateFormat = 'Y-m-d H:i:s')
    {
        $this->logFile = $logFile;
        $this->dateFormat = $dateFormat;
    }

    public function log(string $level, string $message): void
    {
        $timestamp = date($this->dateFormat);
        $logMessage = "[{$timestamp}] [{$level}] {$message}" . PHP_EOL;
        file_put_contents($this->logFile, $logMessage, FILE_APPEND | LOCK_EX);
    }

    public function info(string $message): void
    {
        $this->log('INFO', $message);
    }

    public function error(string $message): void
    {
        $this->log('ERROR', $message);
    }

    public function debug(string $message): void
    {
        $this->log('DEBUG', $message);
    }

    public function warning(string $message): void
    {
        $this->log('WARNING', $message);
    }

    public function setLogFile(string $logFile): void
    {
        $this->logFile = $logFile;
    }

    public function setDateFormat(string $dateFormat): void
    {
        $this->dateFormat = $dateFormat;
    }
}