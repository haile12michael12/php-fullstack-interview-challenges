<?php

namespace Src\Query;

use Src\Grammar\GrammarInterface;
use PDO;

class UpdateQuery extends QueryBuilder
{
    /**
     * Create a new update query instance
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
    public function table(string $table): self
    {
        $this->components['table'] = $table;
        return $this;
    }

    /**
     * Set the values for the update query
     *
     * @param array $values
     * @return self
     */
    public function set(array $values): self
    {
        $this->components['values'] = $values;
        
        // Add bindings for each value
        foreach ($values as $value) {
            $this->bindings[] = $value;
        }
        
        return $this;
    }

    /**
     * Execute the update statement
     *
     * @return int
     */
    public function execute(): int
    {
        return parent::update($this->components['values']);
    }
}