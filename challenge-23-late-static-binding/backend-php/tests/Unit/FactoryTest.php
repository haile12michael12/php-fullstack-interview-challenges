<?php

use PHPUnit\Framework\TestCase;
use App\ORM\Factory;

class FactoryTest extends TestCase
{
    public function testFactoryInstantiation()
    {
        $this->assertTrue(class_exists(Factory::class));
    }
    
    public function testFactoryDefine()
    {
        Factory::define('TestModel', function () {
            return ['name' => 'Test'];
        });
        
        $this->assertTrue(true); // If we get here, the define method worked
    }
    
    public function testFactoryState()
    {
        Factory::state('TestModel', 'active', function () {
            return ['active' => true];
        });
        
        $this->assertTrue(true); // If we get here, the state method worked
    }
    
    public function testFactoryMake()
    {
        Factory::define('TestModel', function () {
            return ['name' => 'Test'];
        });
        
        $model = Factory::make('TestModel');
        $this->assertIsObject($model);
    }
}