<?php

namespace App\Repository;

/**
 * Event repository interface
 * 
 * Defines the contract for event storage implementations
 */
interface EventRepositoryInterface
{
    /**
     * Save an event
     *
     * @param array $event The event data to save
     * @return bool True if the event was saved successfully, false otherwise
     */
    public function save(array $event): bool;

    /**
     * Find events by type
     *
     * @param string $type The event type to filter by
     * @return array The list of events matching the type
     */
    public function findByType(string $type): array;

    /**
     * Find events by user ID
     *
     * @param string $userId The user ID to filter by
     * @return array The list of events for the user
     */
    public function findByUserId(string $userId): array;

    /**
     * Get all events
     *
     * @return array The list of all events
     */
    public function findAll(): array;

    /**
     * Clear all events
     *
     * @return bool True if events were cleared successfully, false otherwise
     */
    public function clear(): bool;
}