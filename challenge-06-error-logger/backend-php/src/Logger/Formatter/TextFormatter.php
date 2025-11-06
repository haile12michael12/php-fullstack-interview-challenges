<?php

namespace App\Logger\Formatter;

use App\Core\Contracts\FormatterInterface;
use App\Model\LogEntry;

class TextFormatter implements FormatterInterface
{
    public function format(LogEntry $logEntry): string
    {
        $context = !empty($logEntry->getContext()) ? ' ' . json_encode($logEntry->getContext()) : '';
        return sprintf(
            "[%s] %s: %s%s",
            $logEntry->getTimestamp(),
            strtoupper($logEntry->getLevel()),
            $logEntry->getMessage(),
            $context
        );
    }
}