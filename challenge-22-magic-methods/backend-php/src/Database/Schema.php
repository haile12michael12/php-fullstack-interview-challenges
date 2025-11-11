<?php

namespace App\Database;

class Schema
{
    protected Connection $connection;
    protected string $table;
    protected array $columns = [];
    protected array $indexes = [];
    protected array $foreignKeys = [];

    public function __construct(Connection $connection, string $table)
    {
        $this->connection = $connection;
        $this->table = $table;
    }

    public function __call(string $method, array $parameters)
    {
        // Handle column definition methods
        if ($this->isColumnType($method)) {
            $column = $parameters[0];
            $this->addColumn($column, $method);
            return $this;
        }
        
        throw new \BadMethodCallException("Method {$method} does not exist.");
    }

    protected function isColumnType(string $type): bool
    {
        $types = ['string', 'text', 'integer', 'bigInteger', 'smallInteger', 'tinyInteger', 
                  'float', 'double', 'decimal', 'boolean', 'date', 'dateTime', 'time', 
                  'timestamp', 'binary', 'json', 'jsonb'];
        return in_array($type, $types);
    }

    public function addColumn(string $name, string $type, array $options = []): self
    {
        $this->columns[$name] = [
            'type' => $type,
            'options' => $options
        ];
        return $this;
    }

    public function string(string $name, int $length = 255): self
    {
        return $this->addColumn($name, 'string', ['length' => $length]);
    }

    public function text(string $name): self
    {
        return $this->addColumn($name, 'text');
    }

    public function integer(string $name): self
    {
        return $this->addColumn($name, 'integer');
    }

    public function bigInteger(string $name): self
    {
        return $this->addColumn($name, 'bigInteger');
    }

    public function boolean(string $name): self
    {
        return $this->addColumn($name, 'boolean');
    }

    public function timestamps(): self
    {
        $this->addColumn('created_at', 'timestamp');
        $this->addColumn('updated_at', 'timestamp');
        return $this;
    }

    public function nullable(): self
    {
        $lastColumn = array_key_last($this->columns);
        if ($lastColumn) {
            $this->columns[$lastColumn]['options']['nullable'] = true;
        }
        return $this;
    }

    public function default($value): self
    {
        $lastColumn = array_key_last($this->columns);
        if ($lastColumn) {
            $this->columns[$lastColumn]['options']['default'] = $value;
        }
        return $this;
    }

    public function primary(): self
    {
        $lastColumn = array_key_last($this->columns);
        if ($lastColumn) {
            $this->columns[$lastColumn]['options']['primary'] = true;
        }
        return $this;
    }

    public function unique(): self
    {
        $lastColumn = array_key_last($this->columns);
        if ($lastColumn) {
            $this->indexes[] = ['columns' => [$lastColumn], 'type' => 'unique'];
        }
        return $this;
    }

    public function index(array $columns = null): self
    {
        if ($columns === null) {
            $lastColumn = array_key_last($this->columns);
            if ($lastColumn) {
                $columns = [$lastColumn];
            }
        }
        
        if ($columns) {
            $this->indexes[] = ['columns' => $columns, 'type' => 'index'];
        }
        
        return $this;
    }

    public function foreign(string $column): self
    {
        $this->foreignKeys[] = ['column' => $column];
        return $this;
    }

    public function references(string $column): self
    {
        $lastForeignKey = array_key_last($this->foreignKeys);
        if ($lastForeignKey !== null) {
            $this->foreignKeys[$lastForeignKey]['references'] = $column;
        }
        return $this;
    }

    public function on(string $table): self
    {
        $lastForeignKey = array_key_last($this->foreignKeys);
        if ($lastForeignKey !== null) {
            $this->foreignKeys[$lastForeignKey]['on'] = $table;
        }
        return $this;
    }

    public function onDelete(string $action): self
    {
        $lastForeignKey = array_key_last($this->foreignKeys);
        if ($lastForeignKey !== null) {
            $this->foreignKeys[$lastForeignKey]['onDelete'] = $action;
        }
        return $this;
    }

    public function onUpdate(string $action): self
    {
        $lastForeignKey = array_key_last($this->foreignKeys);
        if ($lastForeignKey !== null) {
            $this->foreignKeys[$lastForeignKey]['onUpdate'] = $action;
        }
        return $this;
    }

    public function toSql(): string
    {
        $sql = "CREATE TABLE {$this->table} (";
        
        $columnDefinitions = [];
        foreach ($this->columns as $name => $column) {
            $definition = $this->getColumnDefinition($name, $column);
            $columnDefinitions[] = $definition;
        }
        
        $sql .= implode(', ', $columnDefinitions);
        
        // Add indexes
        foreach ($this->indexes as $index) {
            $columns = implode(', ', $index['columns']);
            $type = $index['type'];
            if ($type === 'unique') {
                $sql .= ", UNIQUE ({$columns})";
            } else {
                $sql .= ", INDEX ({$columns})";
            }
        }
        
        // Add foreign keys
        foreach ($this->foreignKeys as $foreignKey) {
            $column = $foreignKey['column'];
            $references = $foreignKey['references'];
            $on = $foreignKey['on'];
            $sql .= ", FOREIGN KEY ({$column}) REFERENCES {$on}({$references})";
            
            if (isset($foreignKey['onDelete'])) {
                $sql .= " ON DELETE {$foreignKey['onDelete']}";
            }
            
            if (isset($foreignKey['onUpdate'])) {
                $sql .= " ON UPDATE {$foreignKey['onUpdate']}";
            }
        }
        
        $sql .= ")";
        
        return $sql;
    }

    protected function getColumnDefinition(string $name, array $column): string
    {
        $type = $this->mapColumnType($column['type'], $column['options'] ?? []);
        $definition = "`{$name}` {$type}";
        
        $options = $column['options'] ?? [];
        
        if (isset($options['nullable']) && !$options['nullable']) {
            $definition .= " NOT NULL";
        } elseif (!isset($options['nullable']) || $options['nullable']) {
            $definition .= " NULL";
        }
        
        if (isset($options['default'])) {
            $default = $options['default'];
            if (is_string($default)) {
                $default = "'{$default}'";
            } elseif (is_bool($default)) {
                $default = $default ? '1' : '0';
            }
            $definition .= " DEFAULT {$default}";
        }
        
        if (isset($options['primary']) && $options['primary']) {
            $definition .= " PRIMARY KEY";
        }
        
        return $definition;
    }

    protected function mapColumnType(string $type, array $options = []): string
    {
        switch ($type) {
            case 'string':
                $length = $options['length'] ?? 255;
                return "VARCHAR({$length})";
            case 'text':
                return 'TEXT';
            case 'integer':
                return 'INT';
            case 'bigInteger':
                return 'BIGINT';
            case 'smallInteger':
                return 'SMALLINT';
            case 'tinyInteger':
                return 'TINYINT';
            case 'float':
                return 'FLOAT';
            case 'double':
                return 'DOUBLE';
            case 'decimal':
                return 'DECIMAL';
            case 'boolean':
                return 'TINYINT(1)';
            case 'date':
                return 'DATE';
            case 'dateTime':
                return 'DATETIME';
            case 'time':
                return 'TIME';
            case 'timestamp':
                return 'TIMESTAMP';
            case 'binary':
                return 'BLOB';
            case 'json':
                return 'JSON';
            default:
                return 'VARCHAR(255)';
        }
    }

    public function create(): bool
    {
        $sql = $this->toSql();
        return $this->connection->exec($sql) !== false;
    }

    public function __toString(): string
    {
        return $this->toSql();
    }

    public function getTable(): string
    {
        return $this->table;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }
}