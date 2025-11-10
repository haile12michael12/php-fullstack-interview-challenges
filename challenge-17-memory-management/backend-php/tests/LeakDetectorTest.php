<?php

namespace Tests;

use App\Leak\Detector;
use PHPUnit\Framework\TestCase;

/**
 * Test for Leak Detector
 */
class LeakDetectorTest extends TestCase
{
    public function testLeakDetection()
    {
        $detector = new Detector();
        $detector->enableMonitoring();
        
        // Create some objects
        $obj1 = new \stdClass();
        $obj2 = new \stdClass();
        
        $detector->track($obj1, 'Test Object 1');
        $detector->track($obj2, 'Test Object 2');
        
        $leaks = $detector->detectLeaks();
        
        $this->assertIsArray($leaks);
        // Note: Actual leak detection would depend on garbage collector behavior
    }
    
    public function testGCStats()
    {
        $detector = new Detector();
        $stats = $detector->getGCStats();
        
        $this->assertIsArray($stats);
        $this->assertArrayHasKey('runs', $stats);
    }
    
    public function testClearTracked()
    {
        $detector = new Detector();
        
        $obj = new \stdClass();
        $detector->track($obj, 'Test Object');
        
        $detector->clearTracked();
        // This test just verifies the method exists and doesn't throw an error
        $this->assertTrue(true);
    }
}