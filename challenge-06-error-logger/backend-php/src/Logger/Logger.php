<?php

namespace App\Logger;

use App\Core\Contracts\LoggerInterface;
use App\Core\Traits\SingletonTrait;
use App\Model\LogEntry;
use App\Core\Helpers\Utils;

class Logger implements LoggerInterface
{
    use SingletonTrait;

    private array $handlers = [];

    public function addHandler($handler): void
    {
        $this->handlers[] = $handler;
    }

    public function getHandlers(): array
    {
        return $this->handlers;
    }

    public function debug(string $message, array $context = []): void
    {
        $this->log('debug', $message, $context);
    }

    public function info(string $message, array $context = []): void
    {
        $this->log('info', $message, $context);
    }

    public function notice(string $message, array $context = []): void
    {
        $this->log('notice', $message, $context);
    }

    public function warning(string $message, array $context = []): void
    {
        $this->log('warning', $message, $context);
    }

    public function error(string $message, array $context = []): void
    {
        $this->log('error', $message, $context);
    }

    public function critical(string $message, array $context = []): void
    {
        $this->log('critical', $message, $context);
    }

    public function alert(string $message, array $context = []): void
    {
        $this->log('alert', $message, $context);
    }

    public function emergency(string $message, array $context = []): void
    {
        $this->log('emergency', $message, $context);
    }

    private function log(string $level, string $message, array $context = []): void
    {
        $logEntry = new LogEntry();
        $logEntry->setId(Utils::generateId());
        $logEntry->setLevel($level);
        $logEntry->setMessage($message);
        $logEntry->setContext($context);
        $logEntry->setTimestamp(Utils::getCurrentTimestamp());
        $logEntry->setIpAddress(Utils::getIpAddress());
        $logEntry->setUserAgent(Utils::getUserAgent());

        foreach ($this->handlers as $handler) {
            if ($handler->supportsLevel($level)) {
                $handler->handle($logEntry);
            }
        }
    }
}