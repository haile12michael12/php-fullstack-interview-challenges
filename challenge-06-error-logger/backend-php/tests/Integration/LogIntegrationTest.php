<?php

namespace Tests\Integration;

use PHPUnit\Framework\TestCase;
use App\Logger\Logger;
use App\Logger\LogHandler;
use App\Storage\FileStorage;
use App\Logger\Formatter\JsonFormatter;

class LogIntegrationTest extends TestCase
{
    private string $testLogFile;
    private Logger $logger;

    protected function setUp(): void
    {
        $this->testLogFile = __DIR__ . '/test_integration.log';
        
        // Create logger with file storage
        $this->logger = Logger::getInstance();
        
        // Clear handlers
        $reflection = new \ReflectionClass($this->logger);
        $property = $reflection->getProperty('handlers');
        $property->setAccessible(true);
        $property->setValue($this->logger, []);
        
        // Add handler
        $storage = new FileStorage($this->testLogFile);
        $formatter = new JsonFormatter();
        $handler = new LogHandler($storage, $formatter);
        $this->logger->addHandler($handler);
    }

    protected function tearDown(): void
    {
        if (file_exists($this->testLogFile)) {
            unlink($this->testLogFile);
        }
    }

    public function testLoggerWritesToFile(): void
    {
        $this->logger->info('Integration test message', ['test' => true]);
        
        $this->assertFileExists($this->testLogFile);
        $content = file_get_contents($this->testLogFile);
        $this->assertStringContainsString('Integration test message', $content);
    }
}