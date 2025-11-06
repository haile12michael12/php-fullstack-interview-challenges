<?php

namespace App\Storage\Rotation;

class LogRotator
{
    private string $logDirectory;
    private RetentionPolicy $retentionPolicy;
    private Compressor $compressor;

    public function __construct(string $logDirectory, RetentionPolicy $retentionPolicy, Compressor $compressor)
    {
        $this->logDirectory = $logDirectory;
        $this->retentionPolicy = $retentionPolicy;
        $this->compressor = $compressor;
    }

    public function rotateLogs(): void
    {
        $files = glob($this->logDirectory . '/*.log');
        
        foreach ($files as $file) {
            if ($this->retentionPolicy->shouldRotate($file)) {
                $this->rotateFile($file);
            }
        }
    }

    private function rotateFile(string $file): void
    {
        $rotatedFile = $file . '.' . date('Y-m-d');
        
        if (rename($file, $rotatedFile)) {
            if ($this->retentionPolicy->shouldCompress($rotatedFile)) {
                $this->compressor->compress($rotatedFile);
            }
        }
    }
}