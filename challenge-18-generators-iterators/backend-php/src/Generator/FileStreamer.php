<?php

namespace App\Generator;

class FileStreamer
{
    private string $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Generator that yields lines from a file
     */
    public function streamLines(): \Generator
    {
        $handle = fopen($this->filePath, 'r');
        if (!$handle) {
            throw new \RuntimeException("Could not open file: {$this->filePath}");
        }

        while (($line = fgets($handle)) !== false) {
            yield trim($line);
        }

        fclose($handle);
    }

    /**
     * Generator that yields chunks of data from a file
     */
    public function streamChunks(int $chunkSize = 1024): \Generator
    {
        $handle = fopen($this->filePath, 'r');
        if (!$handle) {
            throw new \RuntimeException("Could not open file: {$this->filePath}");
        }

        while (!feof($handle)) {
            $chunk = fread($handle, $chunkSize);
            if ($chunk !== false && strlen($chunk) > 0) {
                yield $chunk;
            }
        }

        fclose($handle);
    }

    /**
     * Generator that yields words from a file
     */
    public function streamWords(): \Generator
    {
        foreach ($this->streamLines() as $line) {
            $words = preg_split('/\s+/', $line);
            foreach ($words as $word) {
                if (!empty(trim($word))) {
                    yield trim($word);
                }
            }
        }
    }
}