<?php

namespace App\Generator;

class AdvancedExamples
{
    /**
     * Generator for Fibonacci sequence
     */
    public function fibonacci(int $limit): \Generator
    {
        $a = 0;
        $b = 1;
        
        for ($i = 0; $i < $limit; $i++) {
            yield $a;
            $temp = $a + $b;
            $a = $b;
            $b = $temp;
        }
    }

    /**
     * Generator for prime numbers using Sieve of Eratosthenes
     */
    public function primes(int $limit): \Generator
    {
        $sieve = array_fill(0, $limit + 1, true);
        $sieve[0] = $sieve[1] = false;
        
        for ($i = 2; $i * $i <= $limit; $i++) {
            if ($sieve[$i]) {
                for ($j = $i * $i; $j <= $limit; $j += $i) {
                    $sieve[$j] = false;
                }
            }
        }
        
        for ($i = 2; $i <= $limit; $i++) {
            if ($sieve[$i]) {
                yield $i;
            }
        }
    }

    /**
     * Generator for directory traversal
     */
    public function traverseDirectory(string $path): \Generator
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );
        
        foreach ($iterator as $file) {
            yield $file->getPathname();
        }
    }

    /**
     * Generator for infinite counter
     */
    public function counter(int $start = 0, int $step = 1): \Generator
    {
        $current = $start;
        while (true) {
            yield $current;
            $current += $step;
        }
    }

    /**
     * Generator for chunking arrays
     */
    public function chunkArray(array $array, int $size): \Generator
    {
        for ($i = 0; $i < count($array); $i += $size) {
            yield array_slice($array, $i, $size);
        }
    }
}