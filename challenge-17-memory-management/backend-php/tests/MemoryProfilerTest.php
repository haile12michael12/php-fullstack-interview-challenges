<?php

namespace Tests;

use App\Memory\Profiler;
use App\Utils\MemoryFormatter;
use PHPUnit\Framework\TestCase;

/**
 * Test for Memory Profiler
 */
class MemoryProfilerTest extends TestCase
{
    public function testSnapshotCreation()
    {
        $profiler = new Profiler();
        
        $snapshot = $profiler->snapshot('Test snapshot');
        
        $this->assertArrayHasKey('label', $snapshot);
        $this->assertArrayHasKey('memory_usage', $snapshot);
        $this->assertArrayHasKey('peak_usage', $snapshot);
        $this->assertEquals('Test snapshot', $snapshot['label']);
    }
    
    public function testMultipleSnapshots()
    {
        $profiler = new Profiler();
        
        $profiler->snapshot('Start');
        $data = range(1, 1000); // Create some data
        $profiler->snapshot('After data creation');
        
        $snapshots = $profiler->getSnapshots();
        
        $this->assertCount(2, $snapshots);
        $this->assertGreaterThan(
            $snapshots[0]['memory_usage'],
            $snapshots[1]['memory_usage']
        );
    }
    
    public function testDiffCalculation()
    {
        $profiler = new Profiler();
        
        $profiler->snapshot('Start');
        $data = range(1, 1000);
        $profiler->snapshot('End');
        
        $diff = $profiler->diff(0, 1);
        
        $this->assertArrayHasKey('memory_diff', $diff);
        $this->assertArrayHasKey('formatted_diff', $diff);
        $this->assertGreaterThan(0, $diff['memory_diff']);
    }
    
    public function testStatsCalculation()
    {
        $profiler = new Profiler();
        
        $profiler->snapshot('Snapshot 1');
        $data = range(1, 1000);
        $profiler->snapshot('Snapshot 2');
        $moreData = range(1, 2000);
        $profiler->snapshot('Snapshot 3');
        
        $stats = $profiler->getStats();
        
        $this->assertArrayHasKey('total_snapshots', $stats);
        $this->assertArrayHasKey('average_memory', $stats);
        $this->assertArrayHasKey('max_memory', $stats);
        $this->assertEquals(3, $stats['total_snapshots']);
    }
    
    public function testMemoryFormatting()
    {
        $formatted = MemoryFormatter::formatBytes(1024);
        $this->assertEquals('1 KB', $formatted);
        
        $formatted = MemoryFormatter::formatBytes(1048576);
        $this->assertEquals('1 MB', $formatted);
    }
}