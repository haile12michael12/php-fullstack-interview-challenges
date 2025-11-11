<?php

namespace App\Contracts;

interface StrategyInterface
{
    public function execute(array $data): mixed;
    public function getName(): string;
}