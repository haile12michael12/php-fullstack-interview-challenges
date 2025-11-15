<?php

namespace App\ValueObject;

/**
 * Abstract base class for all value objects
 * 
 * Value Objects are immutable objects that are defined by their attributes
 * rather than their identity. They play a crucial role in domain-driven design
 * by ensuring data integrity and encapsulating business rules.
 */
abstract class ValueObject
{
    /**
     * Check if this value object is equal to another
     *
     * @param ValueObject $other The other value object to compare with
     * @return bool True if the value objects are equal, false otherwise
     */
    abstract public function equals(ValueObject $other): bool;

    /**
     * Convert the value object to an array representation
     *
     * @return array The array representation of the value object
     */
    abstract public function toArray(): array;

    /**
     * Convert the value object to a string representation
     *
     * @return string The string representation of the value object
     */
    abstract public function __toString(): string;

    /**
     * Default implementation of equality comparison
     * Can be overridden by subclasses for more specific logic
     *
     * @param ValueObject $other The other value object to compare with
     * @return bool True if the value objects are equal, false otherwise
     */
    public function isEqualTo(ValueObject $other): bool
    {
        return $this->equals($other);
    }
}