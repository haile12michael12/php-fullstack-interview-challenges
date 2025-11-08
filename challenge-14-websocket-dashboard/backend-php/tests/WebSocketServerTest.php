<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class WebSocketServerTest extends TestCase
{
    public function testWebSocketServerClassExists()
    {
        $this->assertTrue(class_exists('App\\WebSocket\\WebSocketServer'));
    }
    
    public function testConnectionManagerClassExists()
    {
        $this->assertTrue(class_exists('App\\WebSocket\\ConnectionManager'));
    }
    
    public function testMessageRouterClassExists()
    {
        $this->assertTrue(class_exists('App\\WebSocket\\MessageRouter'));
    }
    
    public function testBroadcastServiceClassExists()
    {
        $this->assertTrue(class_exists('App\\WebSocket\\BroadcastService'));
    }
    
    public function testMetricsCollectorClassExists()
    {
        $this->assertTrue(class_exists('App\\Dashboard\\MetricsCollector'));
    }
    
    public function testDataProviderClassExists()
    {
        $this->assertTrue(class_exists('App\\Dashboard\\DataProvider'));
    }
    
    public function testAnalyticsServiceClassExists()
    {
        $this->assertTrue(class_exists('App\\Dashboard\\AnalyticsService'));
    }
    
    public function testWebSocketExceptionClassExists()
    {
        $this->assertTrue(class_exists('App\\Exception\\WebSocketException'));
    }
    
    public function testConnectionExceptionClassExists()
    {
        $this->assertTrue(class_exists('App\\Exception\\ConnectionException'));
    }
}