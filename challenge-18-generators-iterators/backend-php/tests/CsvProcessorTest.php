<?php

namespace Tests;

use App\Generator\CsvProcessor;
use PHPUnit\Framework\TestCase;

class CsvProcessorTest extends TestCase
{
    private string $testCsvFile;

    protected function setUp(): void
    {
        // Create a temporary CSV file for testing
        $this->testCsvFile = tempnam(sys_get_temp_dir(), 'test_csv');
        $csvContent = "id,name,age\n1,John,25\n2,Jane,30\n3,Bob,35\n4,Alice,28";
        file_put_contents($this->testCsvFile, $csvContent);
    }

    protected function tearDown(): void
    {
        // Clean up the temporary file
        if (file_exists($this->testCsvFile)) {
            unlink($this->testCsvFile);
        }
    }

    public function testProcessCsv(): void
    {
        $csvProcessor = new CsvProcessor($this->testCsvFile);
        $generator = $csvProcessor->processCsv();

        $results = iterator_to_array($generator);

        $this->assertCount(4, $results);
        $this->assertEquals('John', $results[0]['name']);
        $this->assertEquals('35', $results[2]['age']);
    }

    public function testProcessInBatches(): void
    {
        $csvProcessor = new CsvProcessor($this->testCsvFile);
        $generator = $csvProcessor->processInBatches(2);

        $batches = iterator_to_array($generator);

        $this->assertCount(2, $batches);
        $this->assertCount(2, $batches[0]);
        $this->assertCount(2, $batches[1]);
    }
}