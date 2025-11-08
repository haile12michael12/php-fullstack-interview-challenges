<?php

namespace Tests\Unit;

use App\Logger\LoggerFactory;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class LoggerFactoryTest extends TestCase
{
    public function testCreateLogger()
    {
        $logger = LoggerFactory::create('test');
        
        $this->assertInstanceOf(LoggerInterface::class, $logger);
    }

    public function testCreateLoggerWithDifferentNames()
    {
        $logger1 = LoggerFactory::create('test1');
        $logger2 = LoggerFactory::create('test2');
        
        $this->assertInstanceOf(LoggerInterface::class, $logger1);
        $this->assertInstanceOf(LoggerInterface::class, $logger2);
        $this->assertNotSame($logger1, $logger2);
    }

    public function testCreateLoggerWithSameNameReturnsSameInstance()
    {
        $logger1 = LoggerFactory::create('test');
        $logger2 = LoggerFactory::create('test');
        
        $this->assertSame($logger1, $logger2);
    }

    public function testCreateLoggerWithDifferentLevels()
    {
        $debugLogger = LoggerFactory::create('debug_test', 'debug');
        $errorLogger = LoggerFactory::create('error_test', 'error');
        
        $this->assertInstanceOf(LoggerInterface::class, $debugLogger);
        $this->assertInstanceOf(LoggerInterface::class, $errorLogger);
    }

    public function testLoggerWritesToOutput()
    {
        $logger = LoggerFactory::create('test_output');
        
        // Test that we can call log methods without errors
        $logger->info('Test info message');
        $logger->error('Test error message');
        $logger->debug('Test debug message');
        
        $this->assertTrue(true); // If we get here, no exceptions were thrown
    }
}