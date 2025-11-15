<?php

namespace App\Schema;

interface SchemaManagerInterface
{
    public function createTable(string $tableName, array $columns): bool;
    
    public function dropTable(string $tableName): bool;
    
    public function addColumn(string $tableName, string $columnName, array $columnDefinition): bool;
    
    public function dropColumn(string $tableName, string $columnName): bool;
    
    public function getTables(): array;
    
    public function getColumns(string $tableName): array;
}