<?php

namespace App\Storage\Rotation;

class Compressor
{
    public function compress(string $file): bool
    {
        if (!file_exists($file)) {
            return false;
        }
        
        $compressedFile = $file . '.gz';
        
        $fp = gzopen($compressedFile, 'w9');
        if (!$fp) {
            return false;
        }
        
        $content = file_get_contents($file);
        gzwrite($fp, $content);
        gzclose($fp);
        
        // Remove original file
        unlink($file);
        
        return true;
    }

    public function decompress(string $compressedFile): bool
    {
        if (!file_exists($compressedFile) || !str_ends_with($compressedFile, '.gz')) {
            return false;
        }
        
        $file = substr($compressedFile, 0, -3);
        
        $fp = gzopen($compressedFile, 'r');
        if (!$fp) {
            return false;
        }
        
        $content = '';
        while (!gzeof($fp)) {
            $content .= gzread($fp, 4096);
        }
        gzclose($fp);
        
        file_put_contents($file, $content);
        
        return true;
    }
}