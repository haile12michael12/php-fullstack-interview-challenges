<?php

namespace App\Logger;

use App\Core\Contracts\LogHandlerInterface;
use App\Core\Contracts\StorageInterface;
use App\Core\Contracts\FormatterInterface;
use App\Model\LogEntry;

class LogHandler implements LogHandlerInterface
{
    private StorageInterface $storage;
    private FormatterInterface $formatter;
    private array $supportedLevels;

    public function __construct(
        StorageInterface $storage,
        FormatterInterface $formatter,
        array $supportedLevels = []
    ) {
        $this->storage = $storage;
        $this->formatter = $formatter;
        $this->supportedLevels = $supportedLevels;
    }

    public function handle(LogEntry $logEntry): void
    {
        // Format the log entry
        $formattedMessage = $this->formatter->format($logEntry);
        
        // Save to storage
        $this->storage->save($logEntry);
    }

    public function supportsLevel(string $level): bool
    {
        if (empty($this->supportedLevels)) {
            return true;
        }
        
        return in_array($level, $this->supportedLevels);
    }
}