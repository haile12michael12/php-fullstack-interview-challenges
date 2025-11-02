<?php
namespace App\Services;

// This is a placeholder service implementation. When using real gRPC, generated classes will be used.
class EchoService
{
    public function echo(string $text): string
    {
        return 'Echo: ' . strtoupper(trim($text));
    }
}
