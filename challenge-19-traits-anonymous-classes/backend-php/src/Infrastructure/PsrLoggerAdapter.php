<?php

namespace App\Infrastructure;

use App\Traits\LoggerTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class PsrLoggerAdapter implements LoggerInterface
{
    use LoggerTrait;

    public function emergency($message, array $context = []): void
    {
        $this->logMessage($message, LogLevel::EMERGENCY, $context);
    }

    public function alert($message, array $context = []): void
    {
        $this->logMessage($message, LogLevel::ALERT, $context);
    }

    public function critical($message, array $context = []): void
    {
        $this->logMessage($message, LogLevel::CRITICAL, $context);
    }

    public function error($message, array $context = []): void
    {
        $this->logMessage($message, LogLevel::ERROR, $context);
    }

    public function warning($message, array $context = []): void
    {
        $this->logMessage($message, LogLevel::WARNING, $context);
    }

    public function notice($message, array $context = []): void
    {
        $this->logMessage($message, LogLevel::NOTICE, $context);
    }

    public function info($message, array $context = []): void
    {
        $this->logMessage($message, LogLevel::INFO, $context);
    }

    public function debug($message, array $context = []): void
    {
        $this->logMessage($message, LogLevel::DEBUG, $context);
    }

    public function log($level, $message, array $context = []): void
    {
        $this->logMessage($message, $level, $context);
    }

    protected function logMessage(string $message, string $level, array $context = []): void
    {
        // Replace placeholders in message with context values
        foreach ($context as $key => $value) {
            $message = str_replace("{{$key}}", (string)$value, $message);
        }

        $this->log($message, $level);
    }
}