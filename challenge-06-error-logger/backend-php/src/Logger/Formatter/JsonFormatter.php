<?php

namespace App\Logger\Formatter;

use App\Core\Contracts\FormatterInterface;
use App\Model\LogEntry;

class JsonFormatter implements FormatterInterface
{
    public function format(LogEntry $logEntry): string
    {
        return json_encode($logEntry->toArray(), JSON_UNESCAPED_UNICODE);
    }
}