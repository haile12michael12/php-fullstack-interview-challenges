<?php

namespace Tests;

use App\Iterator\CustomCollection;
use App\Iterator\FilterIterator;
use App\Iterator\MapIterator;
use App\Iterator\InfiniteSequence;
use PHPUnit\Framework\TestCase;

class IteratorTest extends TestCase
{
    public function testCustomCollection(): void
    {
        $collection = new CustomCollection([1, 2, 3, 4, 5]);
        
        $results = [];
        foreach ($collection as $item) {
            $results[] = $item;
        }
        
        $this->assertEquals([1, 2, 3, 4, 5], $results);
        $this->assertCount(5, $collection);
    }

    public function testFilterIterator(): void
    {
        $collection = new CustomCollection([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
        $filter = function ($item) {
            return $item % 2 === 0; // Even numbers only
        };
        
        $filtered = new FilterIterator($collection, $filter);
        
        $results = [];
        foreach ($filtered as $item) {
            $results[] = $item;
        }
        
        $this->assertEquals([2, 4, 6, 8, 10], $results);
    }

    public function testMapIterator(): void
    {
        $collection = new CustomCollection([1, 2, 3, 4, 5]);
        $mapper = function ($item) {
            return $item * 2;
        };
        
        $mapped = new MapIterator($collection, $mapper);
        
        $results = [];
        foreach ($mapped as $item) {
            $results[] = $item;
        }
        
        $this->assertEquals([2, 4, 6, 8, 10], $results);
    }

    public function testInfiniteSequence(): void
    {
        $sequence = new InfiniteSequence(0, 2);
        
        $results = [];
        $count = 0;
        foreach ($sequence as $item) {
            $results[] = $item;
            $count++;
            if ($count >= 5) {
                break;
            }
        }
        
        $this->assertEquals([0, 2, 4, 6, 8], $results);
    }
}