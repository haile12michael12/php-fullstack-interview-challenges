<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Logger\Formatter\JsonFormatter;
use App\Logger\Formatter\TextFormatter;
use App\Logger\Formatter\HtmlFormatter;
use App\Model\LogEntry;

class FormatterTest extends TestCase
{
    private LogEntry $logEntry;

    protected function setUp(): void
    {
        $this->logEntry = new LogEntry();
        $this->logEntry->setId('test-id');
        $this->logEntry->setLevel('error');
        $this->logEntry->setMessage('Test message');
        $this->logEntry->setContext(['key' => 'value']);
        $this->logEntry->setTimestamp('2023-01-01 12:00:00');
        $this->logEntry->setIpAddress('127.0.0.1');
        $this->logEntry->setUserAgent('test-agent');
    }

    public function testJsonFormatter(): void
    {
        $formatter = new JsonFormatter();
        $result = $formatter->format($this->logEntry);
        
        $this->assertJson($result);
    }

    public function testTextFormatter(): void
    {
        $formatter = new TextFormatter();
        $result = $formatter->format($this->logEntry);
        
        $this->assertStringContainsString('Test message', $result);
        $this->assertStringContainsString('ERROR', $result);
    }

    public function testHtmlFormatter(): void
    {
        $formatter = new HtmlFormatter();
        $result = $formatter->format($this->logEntry);
        
        $this->assertStringContainsString('Test message', $result);
        $this->assertStringContainsString('error', $result);
    }
}