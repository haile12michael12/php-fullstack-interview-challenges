<?php

use PHPUnit\Framework\TestCase;
use App\Database\Model;
use App\ORM\ActiveRecord;
use App\Models\User;

class ModelTest extends TestCase
{
    public function testModelInstantiation()
    {
        $model = new class extends Model {
            protected static $table = 'test_table';
        };
        
        $this->assertInstanceOf(Model::class, $model);
    }
    
    public function testActiveRecordInstantiation()
    {
        $model = new class extends ActiveRecord {
            protected static $table = 'test_table';
        };
        
        $this->assertInstanceOf(ActiveRecord::class, $model);
    }
    
    public function testUserModel()
    {
        $user = new User();
        $this->assertInstanceOf(User::class, $user);
    }
    
    public function testModelFill()
    {
        $model = new class extends Model {
            protected static $fillable = ['name', 'email'];
        };
        
        $model->fill(['name' => 'John', 'email' => 'john@example.com']);
        $this->assertEquals('John', $model->name);
        $this->assertEquals('john@example.com', $model->email);
    }
    
    public function testModelToArray()
    {
        $model = new class extends Model {
            protected static $fillable = ['name', 'email'];
        };
        
        $model->fill(['name' => 'John', 'email' => 'john@example.com']);
        $array = $model->toArray();
        
        $this->assertArrayHasKey('name', $array);
        $this->assertArrayHasKey('email', $array);
        $this->assertEquals('John', $array['name']);
    }
}