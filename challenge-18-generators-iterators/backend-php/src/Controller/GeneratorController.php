<?php

namespace App\Controller;

use App\Http\Request;
use App\Http\Response;
use App\Service\DataProcessor;
use App\Generator\CsvProcessor;
use App\Generator\FileStreamer;
use App\Generator\AdvancedExamples;

class GeneratorController
{
    private DataProcessor $dataProcessor;

    public function __construct()
    {
        $this->dataProcessor = new DataProcessor();
    }

    /**
     * Process CSV data
     */
    public function processCsv(Request $request): Response
    {
        try {
            $csvFilePath = __DIR__ . '/../data/large_dataset.csv';
            $result = $this->dataProcessor->processCsvData($csvFilePath);
            
            return new Response([
                'success' => true,
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return new Response([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process file stream
     */
    public function processFile(Request $request): Response
    {
        try {
            $fileFilePath = __DIR__ . '/../data/stream.txt';
            $result = $this->dataProcessor->processFileStream($fileFilePath);
            
            return new Response([
                'success' => true,
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return new Response([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate Fibonacci sequence
     */
    public function fibonacci(Request $request, array $params): Response
    {
        try {
            $limit = (int)($params['limit'] ?? 10);
            $result = $this->dataProcessor->generateFibonacci($limit);
            
            return new Response([
                'success' => true,
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return new Response([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate prime numbers
     */
    public function primes(Request $request, array $params): Response
    {
        try {
            $limit = (int)($params['limit'] ?? 30);
            $result = $this->dataProcessor->generatePrimes($limit);
            
            return new Response([
                'success' => true,
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return new Response([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Stream data simulation
     */
    public function stream(Request $request): Response
    {
        try {
            // Simulate streaming data by returning a chunk
            $fileStreamer = new FileStreamer(__DIR__ . '/../data/stream.txt');
            $stream = $fileStreamer->streamLines();
            
            $data = [];
            $count = 0;
            foreach ($stream as $line) {
                $data[] = $line;
                $count++;
                if ($count >= 5) break; // Limit to 5 lines for demo
            }
            
            return new Response([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return new Response([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}