<?php

namespace App\Command;

interface CommandInterface
{
    /**
     * Execute the command
     *
     * @return mixed
     */
    public function execute();

    /**
     * Undo the command
     *
     * @return mixed
     */
    public function undo();

    /**
     * Get command name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Get command description
     *
     * @return string
     */
    public function getDescription(): string;

    /**
     * Get command metadata
     *
     * @return array
     */
    public function getMetadata(): array;
}