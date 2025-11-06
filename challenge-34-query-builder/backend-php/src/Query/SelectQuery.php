<?php

namespace Src\Query;

use Src\Grammar\GrammarInterface;
use PDO;

class SelectQuery extends QueryBuilder
{
    /**
     * Create a new select query instance
     *
     * @param PDO $connection
     * @param GrammarInterface $grammar
     */
    public function __construct(PDO $connection, GrammarInterface $grammar)
    {
        parent::__construct($connection, $grammar);
    }

    /**
     * Set the table which the query is targeting
     *
     * @param string $table
     * @return self
     */
    public function from(string $table): self
    {
        $this->components['from'] = $table;
        return $this;
    }

    /**
     * Execute the query as a select statement
     *
     * @return array
     */
    public function get(): array
    {
        return parent::get();
    }

    /**
     * Execute the query and get the first result
     *
     * @return array|null
     */
    public function first(): ?array
    {
        return parent::first();
    }

    /**
     * Get the count of records
     *
     * @param string $column
     * @return int
     */
    public function count(string $column = '*'): int
    {
        $clone = $this->clone();
        $clone->components['columns'] = ["COUNT({$column}) as aggregate"];
        $result = $clone->first();
        return (int) ($result['aggregate'] ?? 0);
    }

    /**
     * Get the sum of a column
     *
     * @param string $column
     * @return mixed
     */
    public function sum(string $column)
    {
        $clone = $this->clone();
        $clone->components['columns'] = ["SUM({$column}) as aggregate"];
        $result = $clone->first();
        return $result['aggregate'] ?? 0;
    }

    /**
     * Get the average of a column
     *
     * @param string $column
     * @return mixed
     */
    public function avg(string $column)
    {
        $clone = $this->clone();
        $clone->components['columns'] = ["AVG({$column}) as aggregate"];
        $result = $clone->first();
        return $result['aggregate'] ?? 0;
    }

    /**
     * Get the minimum value of a column
     *
     * @param string $column
     * @return mixed
     */
    public function min(string $column)
    {
        $clone = $this->clone();
        $clone->components['columns'] = ["MIN({$column}) as aggregate"];
        $result = $clone->first();
        return $result['aggregate'] ?? null;
    }

    /**
     * Get the maximum value of a column
     *
     * @param string $column
     * @return mixed
     */
    public function max(string $column)
    {
        $clone = $this->clone();
        $clone->components['columns'] = ["MAX({$column}) as aggregate"];
        $result = $clone->first();
        return $result['aggregate'] ?? null;
    }
}