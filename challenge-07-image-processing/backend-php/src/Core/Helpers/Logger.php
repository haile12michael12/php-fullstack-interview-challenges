<?php

namespace App\Core\Helpers;

class Logger
{
    private static string $logFile = __DIR__ . '/../../../logs/app.log';

    public static function info(string $message, array $context = []): void
    {
        self::log('INFO', $message, $context);
    }

    public static function error(string $message, array $context = []): void
    {
        self::log('ERROR', $message, $context);
    }

    public static function warning(string $message, array $context = []): void
    {
        self::log('WARNING', $message, $context);
    }

    private static function log(string $level, string $message, array $context = []): void
    {
        $timestamp = date('Y-m-d H:i:s');
        $contextString = !empty($context) ? json_encode($context) : '';
        $logMessage = "[{$timestamp}] {$level}: {$message} {$contextString}" . PHP_EOL;

        file_put_contents(self::$logFile, $logMessage, FILE_APPEND | LOCK_EX);
    }
}