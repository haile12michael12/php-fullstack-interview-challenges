<?php

namespace App\Receiver;

class DatabaseManager
{
    private array $data = [];
    private string $storagePath;

    public function __construct(string $storagePath = './storage/database.json')
    {
        $this->storagePath = $storagePath;
        $this->loadData();
    }

    /**
     * Insert a record into a table
     *
     * @param string $table
     * @param array $data
     * @return array
     */
    public function insert(string $table, array $data): array
    {
        if (!isset($this->data[$table])) {
            $this->data[$table] = [];
        }

        // Generate ID if not provided
        if (!isset($data['id'])) {
            $data['id'] = $this->generateId($table);
        }

        // Add timestamps
        $data['created_at'] = date('c');
        $data['updated_at'] = date('c');

        $this->data[$table][] = $data;
        $this->saveData();

        return $data;
    }

    /**
     * Select records from a table
     *
     * @param string $table
     * @param array $conditions
     * @return array
     */
    public function select(string $table, array $conditions = []): array
    {
        if (!isset($this->data[$table])) {
            return [];
        }

        if (empty($conditions)) {
            return $this->data[$table];
        }

        $results = [];
        foreach ($this->data[$table] as $record) {
            $match = true;
            foreach ($conditions as $key => $value) {
                if (!isset($record[$key]) || $record[$key] !== $value) {
                    $match = false;
                    break;
                }
            }
            if ($match) {
                $results[] = $record;
            }
        }

        return $results;
    }

    /**
     * Update records in a table
     *
     * @param string $table
     * @param array $data
     * @param array $conditions
     * @return int
     */
    public function update(string $table, array $data, array $conditions = []): int
    {
        if (!isset($this->data[$table])) {
            return 0;
        }

        $updated = 0;
        foreach ($this->data[$table] as &$record) {
            $match = true;
            if (!empty($conditions)) {
                foreach ($conditions as $key => $value) {
                    if (!isset($record[$key]) || $record[$key] !== $value) {
                        $match = false;
                        break;
                    }
                }
            }

            if ($match) {
                // Update the record
                foreach ($data as $key => $value) {
                    $record[$key] = $value;
                }
                $record['updated_at'] = date('c');
                $updated++;
            }
        }

        if ($updated > 0) {
            $this->saveData();
        }

        return $updated;
    }

    /**
     * Delete records from a table
     *
     * @param string $table
     * @param array $conditions
     * @return int
     */
    public function delete(string $table, array $conditions = []): int
    {
        if (!isset($this->data[$table])) {
            return 0;
        }

        if (empty($conditions)) {
            $count = count($this->data[$table]);
            $this->data[$table] = [];
            $this->saveData();
            return $count;
        }

        $deleted = 0;
        foreach ($this->data[$table] as $index => $record) {
            $match = true;
            foreach ($conditions as $key => $value) {
                if (!isset($record[$key]) || $record[$key] !== $value) {
                    $match = false;
                    break;
                }
            }

            if ($match) {
                unset($this->data[$table][$index]);
                $deleted++;
            }
        }

        // Reindex array
        if ($deleted > 0) {
            $this->data[$table] = array_values($this->data[$table]);
            $this->saveData();
        }

        return $deleted;
    }

    /**
     * Get table information
     *
     * @param string $table
     * @return array
     */
    public function getTableInfo(string $table): array
    {
        if (!isset($this->data[$table])) {
            return ['count' => 0];
        }

        return [
            'count' => count($this->data[$table]),
            'columns' => $this->getTableColumns($table)
        ];
    }

    /**
     * Get all tables
     *
     * @return array
     */
    public function getTables(): array
    {
        return array_keys($this->data);
    }

    /**
     * Generate a unique ID for a table
     *
     * @param string $table
     * @return string
     */
    private function generateId(string $table): string
    {
        $maxId = 0;
        if (isset($this->data[$table])) {
            foreach ($this->data[$table] as $record) {
                if (isset($record['id']) && is_numeric($record['id']) && $record['id'] > $maxId) {
                    $maxId = (int)$record['id'];
                }
            }
        }
        return (string)($maxId + 1);
    }

    /**
     * Get column names for a table
     *
     * @param string $table
     * @return array
     */
    private function getTableColumns(string $table): array
    {
        $columns = [];
        if (isset($this->data[$table])) {
            foreach ($this->data[$table] as $record) {
                $columns = array_merge($columns, array_keys($record));
            }
        }
        return array_unique($columns);
    }

    /**
     * Load data from storage
     */
    private function loadData(): void
    {
        if (file_exists($this->storagePath)) {
            $content = file_get_contents($this->storagePath);
            $this->data = json_decode($content, true) ?: [];
        }
    }

    /**
     * Save data to storage
     */
    private function saveData(): void
    {
        $dir = dirname($this->storagePath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        file_put_contents($this->storagePath, json_encode($this->data, JSON_PRETTY_PRINT));
    }
}