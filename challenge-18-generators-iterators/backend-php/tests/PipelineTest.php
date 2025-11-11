<?php

namespace Tests;

use App\Generator\DataPipeline;
use App\Iterator\CustomCollection;
use PHPUnit\Framework\TestCase;

class PipelineTest extends TestCase
{
    public function testPipelineFilterAndMap(): void
    {
        $data = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $collection = new CustomCollection($data);
        
        $pipeline = new DataPipeline();
        
        $result = $pipeline
            ->pipe(DataPipeline::filter(function ($item) {
                return $item % 2 === 0; // Even numbers
            }))
            ->pipe(DataPipeline::map(function ($item) {
                return $item * 2; // Double them
            }))
            ->pipe(DataPipeline::limit(3))
            ->process($collection);
        
        $results = iterator_to_array($result);
        
        $this->assertEquals([4, 8, 12], $results);
    }

    public function testPipelineWithCsvData(): void
    {
        $data = [
            ['name' => 'John', 'age' => '25'],
            ['name' => 'Jane', 'age' => '30'],
            ['name' => 'Bob', 'age' => '35']
        ];
        
        $pipeline = new DataPipeline();
        
        $result = $pipeline
            ->pipe(DataPipeline::filter(function ($row) {
                return (int)$row['age'] >= 30;
            }))
            ->pipe(DataPipeline::map(function ($row) {
                return $row['name'];
            }))
            ->process($data);
        
        $results = iterator_to_array($result);
        
        $this->assertEquals(['Jane', 'Bob'], $results);
    }
}