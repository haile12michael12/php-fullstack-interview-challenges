<?php

namespace Challenge04\Upload;

use Challenge04\Contracts\FileScannerInterface;

class FileScanner implements FileScannerInterface
{
    private array $scanResults = [];

    public function scanFile(string $filePath): array
    {
        if (!file_exists($filePath)) {
            throw new \Exception('File does not exist');
        }
        
        // Simulate scanning process
        $fileId = md5($filePath);
        $isSafe = $this->performSecurityScan($filePath);
        
        $result = [
            'file_id' => $fileId,
            'is_safe' => $isSafe,
            'scan_date' => date('Y-m-d H:i:s'),
            'threats_found' => $isSafe ? [] : ['Generic threat detected'],
            'scan_tool' => 'Simulated Scanner'
        ];
        
        $this->scanResults[$fileId] = $result;
        
        return $result;
    }

    public function isFileSafe(string $filePath): bool
    {
        $fileId = md5($filePath);
        if (isset($this->scanResults[$fileId])) {
            return $this->scanResults[$fileId]['is_safe'];
        }
        
        // Perform scan if not already done
        $result = $this->scanFile($filePath);
        return $result['is_safe'];
    }

    public function getScanResults(string $fileId): array
    {
        return $this->scanResults[$fileId] ?? [];
    }

    private function performSecurityScan(string $filePath): bool
    {
        // In a real implementation, this would interface with ClamAV or similar
        // For simulation, we'll randomly mark some files as unsafe
        return (mt_rand(1, 10) > 1); // 90% chance of being safe
    }
}