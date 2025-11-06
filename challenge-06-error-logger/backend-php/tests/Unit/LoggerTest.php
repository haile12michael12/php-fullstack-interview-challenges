<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Logger\Logger;
use App\Logger\LogHandler;
use App\Storage\FileStorage;
use App\Logger\Formatter\JsonFormatter;

class LoggerTest extends TestCase
{
    public function testLoggerSingleton(): void
    {
        $logger1 = Logger::getInstance();
        $logger2 = Logger::getInstance();
        
        $this->assertSame($logger1, $logger2);
    }

    public function testLoggerAddsHandler(): void
    {
        $logger = Logger::getInstance();
        $storage = $this->createMock(FileStorage::class);
        $formatter = new JsonFormatter();
        $handler = new LogHandler($storage, $formatter);
        
        $logger->addHandler($handler);
        
        $this->assertNotEmpty($logger->getHandlers());
    }
}