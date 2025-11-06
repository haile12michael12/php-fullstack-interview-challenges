<?php

namespace App\Core\Helpers;

class Utils
{
    public static function generateId(): string
    {
        return uniqid('', true);
    }

    public static function getCurrentTimestamp(): string
    {
        return date('Y-m-d H:i:s');
    }

    public static function formatContext(array $context): string
    {
        return json_encode($context, JSON_UNESCAPED_UNICODE);
    }

    public static function getIpAddress(): string
    {
        return $_SERVER['REMOTE_ADDR'] ?? 'cli';
    }

    public static function getUserAgent(): string
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    }
}