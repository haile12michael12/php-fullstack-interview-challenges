<?php

namespace Src\Query;

use Src\Grammar\GrammarInterface;
use PDO;

class InsertQuery extends QueryBuilder
{
    /**
     * Create a new insert query instance
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
    public function into(string $table): self
    {
        $this->components['table'] = $table;
        return $this;
    }

    /**
     * Set the values for the insert query
     *
     * @param array $values
     * @return self
     */
    public function values(array $values): self
    {
        $this->components['values'] = $values;
        
        // Add bindings for each value
        foreach ($values as $value) {
            $this->bindings[] = $value;
        }
        
        return $this;
    }

    /**
     * Execute the insert statement
     *
     * @return bool
     */
    public function execute(): bool
    {
        return parent::insert($this->components['values']);
    }

    /**
     * Get the last inserted ID
     *
     * @return string
     */
    public function getLastInsertId(): string
    {
        return $this->connection->lastInsertId();
    }
}