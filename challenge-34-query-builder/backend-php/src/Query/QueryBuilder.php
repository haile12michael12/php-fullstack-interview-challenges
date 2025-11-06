<?php

namespace Src\Query;

use Src\Grammar\GrammarInterface;
use PDO;

class QueryBuilder
{
    /**
     * The database connection instance
     *
     * @var PDO
     */
    protected PDO $connection;

    /**
     * The grammar implementation
     *
     * @var GrammarInterface
     */
    protected GrammarInterface $grammar;

    /**
     * The components of the query
     *
     * @var array
     */
    protected array $components = [];

    /**
     * The bindings for the query
     *
     * @var array
     */
    protected array $bindings = [];

    /**
     * Create a new query builder instance
     *
     * @param PDO $connection
     * @param GrammarInterface $grammar
     */
    public function __construct(PDO $connection, GrammarInterface $grammar)
    {
        $this->connection = $connection;
        $this->grammar = $grammar;
    }

    /**
     * Set the columns to be selected
     *
     * @param array $columns
     * @return self
     */
    public function select(array $columns = ['*']): self
    {
        $this->components['columns'] = $columns;
        return $this;
    }

    /**
     * Add a new select column(s) to the query
     *
     * @param array $columns
     * @return self
     */
    public function addSelect(array $columns): self
    {
        if (!isset($this->components['columns'])) {
            $this->components['columns'] = [];
        }

        $this->components['columns'] = array_merge($this->components['columns'], $columns);
        return $this;
    }

    /**
     * Force the query to only return distinct results
     *
     * @return self
     */
    public function distinct(): self
    {
        if (!isset($this->components['columns'])) {
            $this->components['columns'] = ['*'];
        }

        $this->components['columns']['distinct'] = true;
        return $this;
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
     * Add a join clause to the query
     *
     * @param string $table
     * @param string $first
     * @param string $operator
     * @param string $second
     * @param string $type
     * @param string $boolean
     * @return self
     */
    public function join(string $table, string $first, string $operator, string $second, string $type = 'inner', string $boolean = 'and'): self
    {
        if (!isset($this->components['joins'])) {
            $this->components['joins'] = [];
        }

        $this->components['joins'][] = [
            'type' => $type,
            'table' => $table,
            'clauses' => [
                [
                    'first' => $first,
                    'operator' => $operator,
                    'second' => $second,
                    'boolean' => $boolean
                ]
            ]
        ];

        return $this;
    }

    /**
     * Add a left join clause to the query
     *
     * @param string $table
     * @param string $first
     * @param string $operator
     * @param string $second
     * @return self
     */
    public function leftJoin(string $table, string $first, string $operator, string $second): self
    {
        return $this->join($table, $first, $operator, $second, 'left');
    }

    /**
     * Add a right join clause to the query
     *
     * @param string $table
     * @param string $first
     * @param string $operator
     * @param string $second
     * @return self
     */
    public function rightJoin(string $table, string $first, string $operator, string $second): self
    {
        return $this->join($table, $first, $operator, $second, 'right');
    }

    /**
     * Add a where clause to the query
     *
     * @param string $column
     * @param string|null $operator
     * @param mixed $value
     * @param string $boolean
     * @return self
     */
    public function where(string $column, ?string $operator = null, $value = null, string $boolean = 'and'): self
    {
        // Handle cases where operator is omitted
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }

        if (!isset($this->components['wheres'])) {
            $this->components['wheres'] = [];
        }

        $this->components['wheres'][] = [
            'type' => 'basic',
            'column' => $column,
            'operator' => $operator,
            'value' => $value,
            'boolean' => $boolean
        ];

        $this->bindings[] = $value;

        return $this;
    }

    /**
     * Add an "or where" clause to the query
     *
     * @param string $column
     * @param string|null $operator
     * @param mixed $value
     * @return self
     */
    public function orWhere(string $column, ?string $operator = null, $value = null): self
    {
        return $this->where($column, $operator, $value, 'or');
    }

    /**
     * Add a "where null" clause to the query
     *
     * @param string $column
     * @param string $boolean
     * @param bool $not
     * @return self
     */
    public function whereNull(string $column, string $boolean = 'and', bool $not = false): self
    {
        if (!isset($this->components['wheres'])) {
            $this->components['wheres'] = [];
        }

        $type = $not ? 'not_null' : 'null';

        $this->components['wheres'][] = [
            'type' => $type,
            'column' => $column,
            'boolean' => $boolean
        ];

        return $this;
    }

    /**
     * Add a "where not null" clause to the query
     *
     * @param string $column
     * @param string $boolean
     * @return self
     */
    public function whereNotNull(string $column, string $boolean = 'and'): self
    {
        return $this->whereNull($column, $boolean, true);
    }

    /**
     * Add a "where in" clause to the query
     *
     * @param string $column
     * @param array $values
     * @param string $boolean
     * @param bool $not
     * @return self
     */
    public function whereIn(string $column, array $values, string $boolean = 'and', bool $not = false): self
    {
        if (!isset($this->components['wheres'])) {
            $this->components['wheres'] = [];
        }

        $type = $not ? 'not_in' : 'in';

        $this->components['wheres'][] = [
            'type' => $type,
            'column' => $column,
            'values' => $values,
            'boolean' => $boolean
        ];

        foreach ($values as $value) {
            $this->bindings[] = $value;
        }

        return $this;
    }

    /**
     * Add a "where not in" clause to the query
     *
     * @param string $column
     * @param array $values
     * @param string $boolean
     * @return self
     */
    public function whereNotIn(string $column, array $values, string $boolean = 'and'): self
    {
        return $this->whereIn($column, $values, $boolean, true);
    }

    /**
     * Add a "where between" clause to the query
     *
     * @param string $column
     * @param array $values
     * @param string $boolean
     * @param bool $not
     * @return self
     */
    public function whereBetween(string $column, array $values, string $boolean = 'and', bool $not = false): self
    {
        if (!isset($this->components['wheres'])) {
            $this->components['wheres'] = [];
        }

        $type = $not ? 'not_between' : 'between';

        $this->components['wheres'][] = [
            'type' => $type,
            'column' => $column,
            'values' => $values,
            'boolean' => $boolean
        ];

        foreach ($values as $value) {
            $this->bindings[] = $value;
        }

        return $this;
    }

    /**
     * Add a "where not between" clause to the query
     *
     * @param string $column
     * @param array $values
     * @param string $boolean
     * @return self
     */
    public function whereNotBetween(string $column, array $values, string $boolean = 'and'): self
    {
        return $this->whereBetween($column, $values, $boolean, true);
    }

    /**
     * Add a "group by" clause to the query
     *
     * @param array $groups
     * @return self
     */
    public function groupBy(array $groups): self
    {
        if (!isset($this->components['groups'])) {
            $this->components['groups'] = [];
        }

        $this->components['groups'] = array_merge($this->components['groups'], $groups);
        return $this;
    }

    /**
     * Add a "having" clause to the query
     *
     * @param string $column
     * @param string $operator
     * @param mixed $value
     * @param string $boolean
     * @return self
     */
    public function having(string $column, string $operator, $value, string $boolean = 'and'): self
    {
        if (!isset($this->components['havings'])) {
            $this->components['havings'] = [];
        }

        $this->components['havings'][] = [
            'column' => $column,
            'operator' => $operator,
            'value' => $value,
            'boolean' => $boolean
        ];

        $this->bindings[] = $value;

        return $this;
    }

    /**
     * Add an "order by" clause to the query
     *
     * @param string $column
     * @param string $direction
     * @return self
     */
    public function orderBy(string $column, string $direction = 'asc'): self
    {
        if (!isset($this->components['orders'])) {
            $this->components['orders'] = [];
        }

        $this->components['orders'][] = [
            'column' => $column,
            'direction' => strtolower($direction) === 'asc' ? 'asc' : 'desc'
        ];

        return $this;
    }

    /**
     * Add a "limit" clause to the query
     *
     * @param int $limit
     * @return self
     */
    public function limit(int $limit): self
    {
        $this->components['limit'] = $limit;
        return $this;
    }

    /**
     * Add an "offset" clause to the query
     *
     * @param int $offset
     * @return self
     */
    public function offset(int $offset): self
    {
        $this->components['offset'] = $offset;
        return $this;
    }

    /**
     * Execute the query as a select statement
     *
     * @return array
     */
    public function get(): array
    {
        $sql = $this->grammar->compileSelect($this->components);
        $statement = $this->connection->prepare($sql);
        $statement->execute($this->bindings);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Execute the query and get the first result
     *
     * @return array|null
     */
    public function first(): ?array
    {
        $result = $this->limit(1)->get();
        return $result ? $result[0] : null;
    }

    /**
     * Execute an insert statement
     *
     * @param array $values
     * @return bool
     */
    public function insert(array $values): bool
    {
        $this->components['values'] = $values;
        $sql = $this->grammar->compileInsert($this->components);
        $statement = $this->connection->prepare($sql);
        return $statement->execute($this->bindings);
    }

    /**
     * Execute an update statement
     *
     * @param array $values
     * @return int
     */
    public function update(array $values): int
    {
        $this->components['values'] = $values;
        $sql = $this->grammar->compileUpdate($this->components);
        $statement = $this->connection->prepare($sql);
        $statement->execute($this->bindings);
        return $statement->rowCount();
    }

    /**
     * Execute a delete statement
     *
     * @return int
     */
    public function delete(): int
    {
        $sql = $this->grammar->compileDelete($this->components);
        $statement = $this->connection->prepare($sql);
        $statement->execute($this->bindings);
        return $statement->rowCount();
    }

    /**
     * Get the SQL representation of the query
     *
     * @return string
     */
    public function toSql(): string
    {
        if (isset($this->components['values'])) {
            // This is an insert/update query
            if (isset($this->components['table'])) {
                if (count($this->components['values']) > 0 && !is_numeric(array_keys($this->components['values'])[0])) {
                    // Associative array, it's an update
                    return $this->grammar->compileUpdate($this->components);
                } else {
                    // It's an insert
                    return $this->grammar->compileInsert($this->components);
                }
            }
        } elseif (isset($this->components['from'])) {
            // This is a select query
            return $this->grammar->compileSelect($this->components);
        }

        return '';
    }

    /**
     * Get the bindings for the query
     *
     * @return array
     */
    public function getBindings(): array
    {
        return $this->bindings;
    }

    /**
     * Clone the query builder instance
     *
     * @return self
     */
    public function clone(): self
    {
        return clone $this;
    }
}