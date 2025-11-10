<?php

namespace Tests;

use App\Optimizer\DataStructureOptimizer;
use App\Optimizer\ObjectPool;
use PHPUnit\Framework\TestCase;

/**
 * Test for Optimizer components
 */
class OptimizerTest extends TestCase
{
    public function testArrayOptimization()
    {
        $optimizer = new DataStructureOptimizer();
        
        // Test with sequential numeric array
        $data = range(1, 100);
        $optimized = $optimizer->optimizeArray($data);
        
        $this->assertIsArray($optimized);
        $this->assertCount(100, $optimized);
    }
    
    public function testObjectPooling()
    {
        // Create a simple test class
        eval('class TestClass { public $value; public function reset() { $this->value = null; } }');
        
        $pool = new ObjectPool('TestClass');
        $pool->prepopulate(5);
        
        $this->assertEquals(5, $pool->getPoolSize());
        
        $obj = $pool->acquire();
        $this->assertInstanceOf('TestClass', $obj);
        
        $pool->release($obj);
        $this->assertEquals(1, $pool->getPoolSize());
    }
    
    public function testRecommendations()
    {
        $optimizer = new DataStructureOptimizer();
        
        // Test with small array
        $smallData = range(1, 10);
        $recommendations = $optimizer->getRecommendations($smallData);
        
        $this->assertIsArray($recommendations);
    }
}