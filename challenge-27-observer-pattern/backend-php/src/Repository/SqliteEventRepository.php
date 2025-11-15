<?php

namespace App\Repository;

/**
 * SQLite event repository implementation
 * 
 * Stores events in an SQLite database
 */
class SqliteEventRepository implements EventRepositoryInterface
{
    private \PDO $pdo;

    /**
     * Constructor
     *
     * @param string $databasePath The path to the SQLite database file
     */
    public function __construct(string $databasePath = ':memory:')
    {
        $this->pdo = new \PDO("sqlite:{$databasePath}");
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->initializeDatabase();
    }

    /**
     * Initialize the database schema
     *
     * @return void
     */
    private function initializeDatabase(): void
    {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS events (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                type TEXT NOT NULL,
                user_id TEXT,
                data TEXT,
                created_at TEXT NOT NULL
            )
        ");
    }

    /**
     * Save an event
     *
     * @param array $event The event data to save
     * @return bool True if the event was saved successfully, false otherwise
     */
    public function save(array $event): bool
    {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO events (type, user_id, data, created_at)
                VALUES (:type, :user_id, :data, :created_at)
            ");

            $stmt->execute([
                ':type' => $event['type'] ?? '',
                ':user_id' => $event['user_id'] ?? null,
                ':data' => json_encode($event),
                ':created_at' => $event['timestamp']->format('Y-m-d H:i:s') ?? date('Y-m-d H:i:s')
            ]);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Find events by type
     *
     * @param string $type The event type to filter by
     * @return array The list of events matching the type
     */
    public function findByType(string $type): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM events WHERE type = :type ORDER BY created_at DESC");
        $stmt->execute([':type' => $type]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Find events by user ID
     *
     * @param string $userId The user ID to filter by
     * @return array The list of events for the user
     */
    public function findByUserId(string $userId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM events WHERE user_id = :user_id ORDER BY created_at DESC");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get all events
     *
     * @return array The list of all events
     */
    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM events ORDER BY created_at DESC");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Clear all events
     *
     * @return bool True if events were cleared successfully, false otherwise
     */
    public function clear(): bool
    {
        try {
            $this->pdo->exec("DELETE FROM events");
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}