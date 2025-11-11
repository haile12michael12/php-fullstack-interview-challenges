<?php

namespace App\Generator;

class DataPipeline
{
    private array $stages = [];

    /**
     * Add a stage to the pipeline
     */
    public function pipe(callable $stage): self
    {
        $this->stages[] = $stage;
        return $this;
    }

    /**
     * Process data through the pipeline using generators
     */
    public function process(iterable $data): \Generator
    {
        $generator = $this->convertToGenerator($data);
        
        foreach ($this->stages as $stage) {
            $generator = $stage($generator);
        }
        
        foreach ($generator as $item) {
            yield $item;
        }
    }

    /**
     * Convert iterable data to generator
     */
    private function convertToGenerator(iterable $data): \Generator
    {
        foreach ($data as $item) {
            yield $item;
        }
    }

    /**
     * Create a pipeline for filtering data
     */
    public static function filter(callable $predicate): callable
    {
        return function (iterable $data) use ($predicate): \Generator {
            foreach ($data as $item) {
                if ($predicate($item)) {
                    yield $item;
                }
            }
        };
    }

    /**
     * Create a pipeline for mapping data
     */
    public static function map(callable $transformer): callable
    {
        return function (iterable $data) use ($transformer): \Generator {
            foreach ($data as $item) {
                yield $transformer($item);
            }
        };
    }

    /**
     * Create a pipeline for limiting data
     */
    public static function limit(int $limit): callable
    {
        return function (iterable $data) use ($limit): \Generator {
            $count = 0;
            foreach ($data as $item) {
                if ($count >= $limit) {
                    break;
                }
                yield $item;
                $count++;
            }
        };
    }
}