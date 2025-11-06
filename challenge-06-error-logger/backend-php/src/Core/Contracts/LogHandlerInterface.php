<?php

namespace App\Core\Contracts;

use App\Model\LogEntry;

interface LogHandlerInterface
{
    public function handle(LogEntry $logEntry): void;
    public function supportsLevel(string $level): bool;
}