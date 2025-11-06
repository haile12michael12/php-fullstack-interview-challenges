<?php

namespace App\Core\Contracts;

use App\Model\LogEntry;

interface FormatterInterface
{
    public function format(LogEntry $logEntry): string;
}