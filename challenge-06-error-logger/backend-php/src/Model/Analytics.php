<?php

namespace App\Model;

class Analytics
{
    private array $errorCountsByLevel;
    private array $errorCountsByDay;
    private int $totalErrors;
    private float $averageErrorsPerDay;
    private array $topErrors;

    public function __construct()
    {
        $this->errorCountsByLevel = [];
        $this->errorCountsByDay = [];
        $this->topErrors = [];
        $this->totalErrors = 0;
        $this->averageErrorsPerDay = 0.0;
    }

    public function getErrorCountsByLevel(): array
    {
        return $this->errorCountsByLevel;
    }

    public function setErrorCountsByLevel(array $errorCountsByLevel): void
    {
        $this->errorCountsByLevel = $errorCountsByLevel;
    }

    public function getErrorCountsByDay(): array
    {
        return $this->errorCountsByDay;
    }

    public function setErrorCountsByDay(array $errorCountsByDay): void
    {
        $this->errorCountsByDay = $errorCountsByDay;
    }

    public function getTotalErrors(): int
    {
        return $this->totalErrors;
    }

    public function setTotalErrors(int $totalErrors): void
    {
        $this->totalErrors = $totalErrors;
    }

    public function getAverageErrorsPerDay(): float
    {
        return $this->averageErrorsPerDay;
    }

    public function setAverageErrorsPerDay(float $averageErrorsPerDay): void
    {
        $this->averageErrorsPerDay = $averageErrorsPerDay;
    }

    public function getTopErrors(): array
    {
        return $this->topErrors;
    }

    public function setTopErrors(array $topErrors): void
    {
        $this->topErrors = $topErrors;
    }
}