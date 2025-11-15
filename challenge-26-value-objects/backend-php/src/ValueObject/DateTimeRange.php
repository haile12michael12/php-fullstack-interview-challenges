<?php

namespace App\ValueObject;

use DateTimeImmutable;
use DateInterval;
use DateTime;
use InvalidArgumentException;

/**
 * DateTime range value object with validation
 * 
 * Represents a date/time range as an immutable value object.
 */
final class DateTimeRange extends ValueObject
{
    private DateTimeImmutable $start;
    private DateTimeImmutable $end;

    /**
     * Create a new DateTimeRange value object
     *
     * @param DateTimeImmutable $start The start date/time
     * @param DateTimeImmutable $end The end date/time
     * @throws InvalidArgumentException If start is not before end
     */
    public function __construct(DateTimeImmutable $start, DateTimeImmutable $end)
    {
        if ($start >= $end) {
            throw new InvalidArgumentException("Start date must be before end date");
        }

        $this->start = $start;
        $this->end = $end;
    }

    /**
     * Check if this date/time range is equal to another
     *
     * @param ValueObject $other The other value object to compare with
     * @return bool True if the date/time ranges are equal, false otherwise
     */
    public function equals(ValueObject $other): bool
    {
        return $other instanceof self &&
               $this->start == $other->start &&
               $this->end == $other->end;
    }

    /**
     * Get the start date/time
     *
     * @return DateTimeImmutable The start date/time
     */
    public function getStart(): DateTimeImmutable
    {
        return $this->start;
    }

    /**
     * Get the end date/time
     *
     * @return DateTimeImmutable The end date/time
     */
    public function getEnd(): DateTimeImmutable
    {
        return $this->end;
    }

    /**
     * Get the duration of the range
     *
     * @return DateInterval The duration
     */
    public function getDuration(): DateInterval
    {
        return $this->start->diff($this->end);
    }

    /**
     * Check if a date/time is contained within this range
     *
     * @param DateTimeImmutable $date The date/time to check
     * @return bool True if the date/time is contained within the range, false otherwise
     */
    public function contains(DateTimeImmutable $date): bool
    {
        return $date >= $this->start && $date <= $this->end;
    }

    /**
     * Check if this range overlaps with another range
     *
     * @param DateTimeRange $other The other range to check
     * @return bool True if the ranges overlap, false otherwise
     */
    public function overlaps(DateTimeRange $other): bool
    {
        return $this->start < $other->end && $this->end > $other->start;
    }

    /**
     * Convert the date/time range to a string representation
     *
     * @return string The formatted date/time range
     */
    public function __toString(): string
    {
        return $this->start->format('Y-m-d H:i:s') . ' to ' . $this->end->format('Y-m-d H:i:s');
    }

    /**
     * Convert the date/time range to an array representation
     *
     * @return array The array representation of the date/time range
     */
    public function toArray(): array
    {
        return [
            'start' => $this->start->format(DateTime::ISO8601),
            'end' => $this->end->format(DateTime::ISO8601),
            'duration' => $this->getDuration()->format('%d days, %h hours, %i minutes'),
        ];
    }
}