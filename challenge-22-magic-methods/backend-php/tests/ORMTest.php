<?php

use PHPUnit\Framework\TestCase;
use App\ORM\Entity;
use App\ORM\Model;
use App\ORM\Exception\ORMException;
use App\ORM\Exception\MagicMethodException;

class ORMTest extends TestCase
{
    public function testEntityMagicMethods()
    {
        $entity = new class extends Entity {
            protected static array $fillable = ['name', 'email'];
        };
        
        // Test __set and __get
        $entity->name = 'John Doe';
        $entity->email = 'john@example.com';
        
        $this->assertEquals('John Doe', $entity->name);
        $this->assertEquals('john@example.com', $entity->email);
        
        // Test __isset
        $this->assertTrue(isset($entity->name));
        $this->assertFalse(isset($entity->age));
        
        // Test __unset
        unset($entity->email);
        $this->assertFalse(isset($entity->email));
        
        // Test __toString
        $this->assertIsString((string)$entity);
    }
    
    public function testModelMagicMethods()
    {
        // Create a mock model
        $model = new class extends Model {
            protected static string $table = 'test_table';
            protected static array $fillable = ['name', 'email'];
        };
        
        // Test __callStatic
        $this->assertInstanceOf(Model::class, $model);
        
        // Test __call
        $this->assertIsObject($model->query());
    }
    
    public function testORMException()
    {
        $exception = new ORMException('Test exception');
        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertEquals('Test exception', $exception->getMessage());
    }
    
    public function testMagicMethodException()
    {
        $exception = new MagicMethodException('testMethod', 'TestClass');
        $this->assertInstanceOf(ORMException::class, $exception);
        $this->assertStringContainsString('testMethod', $exception->getMessage());
        $this->assertStringContainsString('TestClass', $exception->getMessage());
    }
}