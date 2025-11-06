<?php

namespace Src\Grammar;

class MySQLGrammar implements GrammarInterface
{
    /**
     * Compile a select query
     *
     * @param array $components
     * @return string
     */
    public function compileSelect(array $components): string
    {
        $sql = $this->compileColumns($components['columns'] ?? ['*']);

        if (isset($components['from'])) {
            $sql .= ' FROM ' . $this->wrapTable($components['from']);
        }

        if (isset($components['joins'])) {
            $sql .= ' ' . $this->compileJoins($components['joins']);
        }

        if (isset($components['wheres'])) {
            $sql .= ' ' . $this->compileWheres($components['wheres']);
        }

        if (isset($components['groups'])) {
            $sql .= ' ' . $this->compileGroups($components['groups']);
        }

        if (isset($components['havings'])) {
            $sql .= ' ' . $this->compileHavings($components['havings']);
        }

        if (isset($components['orders'])) {
            $sql .= ' ' . $this->compileOrders($components['orders']);
        }

        if (isset($components['limit'])) {
            $sql .= ' ' . $this->compileLimit($components['limit']);
        }

        if (isset($components['offset'])) {
            $sql .= ' ' . $this->compileOffset($components['offset']);
        }

        return $sql;
    }

    /**
     * Compile the columns for a select query
     *
     * @param array $columns
     * @return string
     */
    protected function compileColumns(array $columns): string
    {
        if (empty($columns)) {
            $columns = ['*'];
        }

        $select = 'SELECT ';

        if (isset($columns['distinct']) && $columns['distinct']) {
            $select .= 'DISTINCT ';
        }

        $columnList = [];
        foreach ($columns as $key => $column) {
            if ($key === 'distinct') {
                continue;
            }
            $columnList[] = $this->wrap($column);
        }

        return $select . implode(', ', $columnList);
    }

    /**
     * Compile the "from" portion of the query
     *
     * @param string $table
     * @return string
     */
    protected function wrapTable(string $table): string
    {
        return $this->wrap($table);
    }

    /**
     * Compile the join clauses for a query
     *
     * @param array $joins
     * @return string
     */
    protected function compileJoins(array $joins): string
    {
        $sql = [];
        foreach ($joins as $join) {
            $table = $this->wrapTable($join['table']);
            $clauses = [];
            foreach ($join['clauses'] as $clause) {
                $clauses[] = $clause['boolean'] . ' ' . $this->wrap($clause['first']) . ' ' . $clause['operator'] . ' ' . $this->wrap($clause['second']);
            }
            $sql[] = $join['type'] . ' JOIN ' . $table . ' ON ' . implode(' ', $clauses);
        }
        return implode(' ', $sql);
    }

    /**
     * Compile the where clauses for a query
     *
     * @param array $wheres
     * @return string
     */
    protected function compileWheres(array $wheres): string
    {
        if (empty($wheres)) {
            return '';
        }

        $sql = 'WHERE ';
        $clauses = [];
        foreach ($wheres as $where) {
            $clauses[] = $this->compileWhere($where);
        }

        return $sql . implode(' ', $clauses);
    }

    /**
     * Compile a single where clause
     *
     * @param array $where
     * @return string
     */
    protected function compileWhere(array $where): string
    {
        $boolean = strtoupper($where['boolean'] ?? 'and');
        
        switch ($where['type']) {
            case 'basic':
                return $boolean . ' ' . $this->wrap($where['column']) . ' ' . $where['operator'] . ' ' . $this->parameter($where['value']);
            case 'null':
                return $boolean . ' ' . $this->wrap($where['column']) . ' IS NULL';
            case 'not_null':
                return $boolean . ' ' . $this->wrap($where['column']) . ' IS NOT NULL';
            case 'in':
                $values = array_map([$this, 'parameter'], $where['values']);
                return $boolean . ' ' . $this->wrap($where['column']) . ' IN (' . implode(', ', $values) . ')';
            case 'not_in':
                $values = array_map([$this, 'parameter'], $where['values']);
                return $boolean . ' ' . $this->wrap($where['column']) . ' NOT IN (' . implode(', ', $values) . ')';
            case 'between':
                return $boolean . ' ' . $this->wrap($where['column']) . ' BETWEEN ' . $this->parameter($where['values'][0]) . ' AND ' . $this->parameter($where['values'][1]);
            case 'not_between':
                return $boolean . ' ' . $this->wrap($where['column']) . ' NOT BETWEEN ' . $this->parameter($where['values'][0]) . ' AND ' . $this->parameter($where['values'][1]);
            default:
                return '';
        }
    }

    /**
     * Compile the group by clauses for a query
     *
     * @param array $groups
     * @return string
     */
    protected function compileGroups(array $groups): string
    {
        return 'GROUP BY ' . implode(', ', array_map([$this, 'wrap'], $groups));
    }

    /**
     * Compile the having clauses for a query
     *
     * @param array $havings
     * @return string
     */
    protected function compileHavings(array $havings): string
    {
        if (empty($havings)) {
            return '';
        }

        $sql = 'HAVING ';
        $clauses = [];
        foreach ($havings as $having) {
            $clauses[] = $this->compileHaving($having);
        }

        return $sql . implode(' ', $clauses);
    }

    /**
     * Compile a single having clause
     *
     * @param array $having
     * @return string
     */
    protected function compileHaving(array $having): string
    {
        $boolean = strtoupper($having['boolean'] ?? 'and');
        return $boolean . ' ' . $this->wrap($having['column']) . ' ' . $having['operator'] . ' ' . $this->parameter($having['value']);
    }

    /**
     * Compile the order by clauses for a query
     *
     * @param array $orders
     * @return string
     */
    protected function compileOrders(array $orders): string
    {
        $compiled = [];
        foreach ($orders as $order) {
            $compiled[] = $this->wrap($order['column']) . ' ' . strtoupper($order['direction']);
        }
        return 'ORDER BY ' . implode(', ', $compiled);
    }

    /**
     * Compile the limit clause for a query
     *
     * @param int $limit
     * @return string
     */
    protected function compileLimit(int $limit): string
    {
        return 'LIMIT ' . $limit;
    }

    /**
     * Compile the offset clause for a query
     *
     * @param int $offset
     * @return string
     */
    protected function compileOffset(int $offset): string
    {
        return 'OFFSET ' . $offset;
    }

    /**
     * Compile an insert query
     *
     * @param array $components
     * @return string
     */
    public function compileInsert(array $components): string
    {
        $table = $this->wrapTable($components['table']);
        $columns = array_keys($components['values']);
        $wrappedColumns = array_map([$this, 'wrap'], $columns);
        
        $parameters = array_map([$this, 'parameter'], array_values($components['values']));
        
        return "INSERT INTO {$table} (" . implode(', ', $wrappedColumns) . ") VALUES (" . implode(', ', $parameters) . ")";
    }

    /**
     * Compile an update query
     *
     * @param array $components
     * @return string
     */
    public function compileUpdate(array $components): string
    {
        $table = $this->wrapTable($components['table']);
        
        $columns = [];
        foreach ($components['values'] as $column => $value) {
            $columns[] = $this->wrap($column) . ' = ' . $this->parameter($value);
        }
        
        $sql = "UPDATE {$table} SET " . implode(', ', $columns);
        
        if (isset($components['wheres'])) {
            $sql .= ' ' . $this->compileWheres($components['wheres']);
        }
        
        return $sql;
    }

    /**
     * Compile a delete query
     *
     * @param array $components
     * @return string
     */
    public function compileDelete(array $components): string
    {
        $table = $this->wrapTable($components['table']);
        $sql = "DELETE FROM {$table}";
        
        if (isset($components['wheres'])) {
            $sql .= ' ' . $this->compileWheres($components['wheres']);
        }
        
        return $sql;
    }

    /**
     * Get the parameter placeholder
     *
     * @param string $value
     * @return string
     */
    public function parameter(string $value): string
    {
        return '?';
    }

    /**
     * Wrap a value in keyword identifiers
     *
     * @param string $value
     * @return string
     */
    public function wrap(string $value): string
    {
        if ($value === '*') {
            return $value;
        }

        return '`' . str_replace('`', '``', $value) . '`';
    }

    /**
     * Get the format for database stored dates
     *
     * @return string
     */
    public function getDateFormat(): string
    {
        return 'Y-m-d H:i:s';
    }
}