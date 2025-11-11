<?php

namespace App\Service;

use App\Generator\CsvProcessor;
use App\Generator\FileStreamer;
use App\Generator\DataPipeline;
use App\Generator\AdvancedExamples;

class DataProcessor
{
    /**
     * Process CSV data with filtering and mapping
     */
    public function processCsvData(string $filePath): array
    {
        $csvProcessor = new CsvProcessor($filePath);
        
        // Create a pipeline for processing
        $pipeline = new DataPipeline();
        
        // Filter users over 30 years old and map to a simpler structure
        $result = $pipeline
            ->pipe(DataPipeline::filter(function ($row) {
                return (int)$row['age'] > 30;
            }))
            ->pipe(DataPipeline::map(function ($row) {
                return [
                    'name' => $row['name'],
                    'age' => (int)$row['age'],
                    'city' => $row['city']
                ];
            }))
            ->pipe(DataPipeline::limit(5))
            ->process($csvProcessor->processCsv());
        
        return iterator_to_array($result);
    }

    /**
     * Process file stream with word counting
     */
    public function processFileStream(string $filePath): array
    {
        $fileStreamer = new FileStreamer($filePath);
        
        $wordCount = [];
        
        // Count words using generator
        foreach ($fileStreamer->streamWords() as $word) {
            $word = strtolower($word);
            if (!isset($wordCount[$word])) {
                $wordCount[$word] = 0;
            }
            $wordCount[$word]++;
        }
        
        // Sort by count descending
        arsort($wordCount);
        
        // Return top 10 words
        return array_slice($wordCount, 0, 10, true);
    }

    /**
     * Generate Fibonacci sequence
     */
    public function generateFibonacci(int $limit): array
    {
        $advanced = new AdvancedExamples();
        return iterator_to_array($advanced->fibonacci($limit));
    }

    /**
     * Generate prime numbers
     */
    public function generatePrimes(int $limit): array
    {
        $advanced = new AdvancedExamples();
        return iterator_to_array($advanced->primes($limit));
    }
}