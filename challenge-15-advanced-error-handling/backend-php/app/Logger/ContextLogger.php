<?php

namespace App\Logger;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;

class ContextLogger implements LoggerInterface
{
    use LoggerTrait;

    private LoggerInterface $logger;
    private array $context;

    public function __construct(LoggerInterface $logger, array $context = [])
    {
        $this->logger = $logger;
        $this->context = $context;
    }

    public function log($level, $message, array $context = []): void
    {
        $mergedContext = array_merge($this->context, $context);
        $this->logger->log($level, $message, $mergedContext);
    }

    public function withContext(array $context): self
    {
        $newContext = array_merge($this->context, $context);
        return new self($this->logger, $newContext);
    }

    public function getContext(): array
    {
        return $this->context;
    }
}