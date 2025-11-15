<?php

namespace App\Command;

use App\Receiver\DatabaseManager;

class DatabaseCommand extends AbstractCommand
{
    private DatabaseManager $databaseManager;
    private string $operation;
    private string $table;
    private array $data;
    private array $conditions;
    private array $backupData;

    public function __construct(
        DatabaseManager $databaseManager,
        string $operation,
        string $table,
        array $data = [],
        array $conditions = []
    ) {
        parent::__construct(
            'Database Operation',
            "$operation record in $table",
            [
                'operation' => $operation,
                'table' => $table,
                'data' => $data,
                'conditions' => $conditions
            ]
        );
        
        $this->databaseManager = $databaseManager;
        $this->operation = $operation;
        $this->table = $table;
        $this->data = $data;
        $this->conditions = $conditions;
        $this->backupData = [];
    }

    /**
     * Execute the command
     *
     * @return mixed
     */
    public function execute()
    {
        switch ($this->operation) {
            case 'insert':
                $result = $this->databaseManager->insert($this->table, $this->data);
                break;
                
            case 'update':
                // Backup existing data for undo
                $this->backupData = $this->databaseManager->select($this->table, $this->conditions);
                $result = $this->databaseManager->update($this->table, $this->data, $this->conditions);
                break;
                
            case 'delete':
                // Backup existing data for undo
                $this->backupData = $this->databaseManager->select($this->table, $this->conditions);
                $result = $this->databaseManager->delete($this->table, $this->conditions);
                break;
                
            default:
                throw new \InvalidArgumentException("Unsupported database operation: {$this->operation}");
        }
        
        $this->markAsExecuted();
        return $result;
    }

    /**
     * Undo the command
     *
     * @return mixed
     */
    public function undo()
    {
        switch ($this->operation) {
            case 'insert':
                // For insert, we delete the inserted record
                $result = $this->databaseManager->delete($this->table, ['id' => $this->data['id'] ?? null]);
                break;
                
            case 'update':
                // For update, we restore the original data
                $result = $this->databaseManager->update($this->table, $this->backupData[0] ?? [], $this->conditions);
                break;
                
            case 'delete':
                // For delete, we re-insert the deleted records
                foreach ($this->backupData as $record) {
                    $this->databaseManager->insert($this->table, $record);
                }
                $result = true;
                break;
                
            default:
                throw new \InvalidArgumentException("Unsupported database operation: {$this->operation}");
        }
        
        $this->markAsUndone();
        return $result;
    }
}