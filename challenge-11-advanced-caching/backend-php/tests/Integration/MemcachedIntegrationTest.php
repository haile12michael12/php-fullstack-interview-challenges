<?php

namespace Tests\Integration;

use App\Cache\MemcachedAdapter;
use PHPUnit\Framework\TestCase;

class MemcachedIntegrationTest extends TestCase
{
    private MemcachedAdapter $memcachedAdapter;
    private \Memcached $memcached;

    protected function setUp(): void
    {
        if (!class_exists('Memcached')) {
            $this->markTestSkipped('Memcached extension not available');
        }

        $this->memcached = new \Memcached();
        $this->memcached->addServer('127.0.0.1', 11211);
        $this->memcached->flush(); // Clear any existing data
        
        $this->memcachedAdapter = new MemcachedAdapter($this->memcached);
    }

    public function testSetAndGet(): void
    {
        $key = 'integration_test_key';
        $value = 'integration_test_value';
        
        $this->assertTrue($this->memcachedAdapter->set($key, $value));
        $this->assertEquals($value, $this->memcachedAdapter->get($key));
    }

    public function testSetWithTtl(): void
    {
        $key = 'ttl_test_key';
        $value = 'ttl_test_value';
        $ttl = 1; // 1 second
        
        $this->assertTrue($this->memcachedAdapter->set($key, $value, $ttl));
        $this->assertEquals($value, $this->memcachedAdapter->get($key));
        
        // Wait for expiration
        sleep($ttl + 1);
        
        $this->assertNull($this->memcachedAdapter->get($key));
    }

    public function testGetMultiple(): void
    {
        $values = [
            'key1' => 'value1',
            'key2' => 'value2',
            'key3' => 'value3'
        ];
        
        foreach ($values as $key => $value) {
            $this->memcachedAdapter->set($key, $value);
        }
        
        $result = $this->memcachedAdapter->getMultiple(array_keys($values));
        $this->assertEquals($values, $result);
    }

    public function testSetMultiple(): void
    {
        $values = [
            'multi_key1' => 'multi_value1',
            'multi_key2' => 'multi_value2',
            'multi_key3' => 'multi_value3'
        ];
        
        $this->assertTrue($this->memcachedAdapter->setMultiple($values));
        
        foreach ($values as $key => $value) {
            $this->assertEquals($value, $this->memcachedAdapter->get($key));
        }
    }
}