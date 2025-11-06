<?php

namespace App\Logger\Formatter;

use App\Core\Contracts\FormatterInterface;
use App\Model\LogEntry;

class HtmlFormatter implements FormatterInterface
{
    public function format(LogEntry $logEntry): string
    {
        $context = !empty($logEntry->getContext()) ? '<br><pre>' . htmlspecialchars(json_encode($logEntry->getContext(), JSON_PRETTY_PRINT)) . '</pre>' : '';
        
        return sprintf(
            '<div class="log-entry log-%s">' .
            '<span class="timestamp">[%s]</span> ' .
            '<span class="level">%s:</span> ' .
            '<span class="message">%s</span>' .
            '%s' .
            '</div>',
            htmlspecialchars($logEntry->getLevel()),
            htmlspecialchars($logEntry->getTimestamp()),
            strtoupper(htmlspecialchars($logEntry->getLevel())),
            htmlspecialchars($logEntry->getMessage()),
            $context
        );
    }
}