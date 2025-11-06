<?php

namespace Src\Grammar;

interface GrammarInterface
{
    /**
     * Compile a select query
     *
     * @param array $components
     * @return string
     */
    public function compileSelect(array $components): string;

    /**
     * Compile an insert query
     *
     * @param array $components
     * @return string
     */
    public function compileInsert(array $components): string;

    /**
     * Compile an update query
     *
     * @param array $components
     * @return string
     */
    public function compileUpdate(array $components): string;

    /**
     * Compile a delete query
     *
     * @param array $components
     * @return string
     */
    public function compileDelete(array $components): string;

    /**
     * Get the parameter placeholder
     *
     * @param string $value
     * @return string
     */
    public function parameter(string $value): string;

    /**
     * Wrap a value in keyword identifiers
     *
     * @param string $value
     * @return string
     */
    public function wrap(string $value): string;

    /**
     * Get the format for database stored dates
     *
     * @return string
     */
    public function getDateFormat(): string;
}