<?php

namespace App\Generator;

class CsvProcessor
{
    private string $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Generator that yields rows from a CSV file
     */
    public function processCsv(): \Generator
    {
        $handle = fopen($this->filePath, 'r');
        if (!$handle) {
            throw new \RuntimeException("Could not open file: {$this->filePath}");
        }

        // Read header row
        $header = fgetcsv($handle);
        
        // Yield each data row
        while (($row = fgetcsv($handle)) !== false) {
            yield array_combine($header, $row);
        }

        fclose($handle);
    }

    /**
     * Generator that processes CSV in batches
     */
    public function processInBatches(int $batchSize = 10): \Generator
    {
        $batch = [];
        $count = 0;

        foreach ($this->processCsv() as $row) {
            $batch[] = $row;
            $count++;

            if ($count >= $batchSize) {
                yield $batch;
                $batch = [];
                $count = 0;
            }
        }

        // Yield remaining items
        if (!empty($batch)) {
            yield $batch;
        }
    }
}