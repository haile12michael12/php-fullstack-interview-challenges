<?php

namespace App\ValueObject;

use InvalidArgumentException;

/**
 * Money value object with currency support
 * 
 * Represents a monetary amount with currency as an immutable value object.
 */
final class Money extends ValueObject
{
    private int $amount; // Stored in cents/smallest currency unit
    private string $currency;

    /**
     * Create a new Money value object
     *
     * @param int $amount The amount in cents (or smallest currency unit)
     * @param string $currency The 3-letter currency code
     * @throws InvalidArgumentException If the amount or currency is invalid
     */
    public function __construct(int $amount, string $currency)
    {
        $this->validate($amount, $currency);
        $this->amount = $amount;
        $this->currency = strtoupper($currency);
    }

    /**
     * Validate the amount and currency
     *
     * @param int $amount The amount to validate
     * @param string $currency The currency to validate
     * @throws InvalidArgumentException If the amount or currency is invalid
     */
    private function validate(int $amount, string $currency): void
    {
        if ($amount < 0) {
            throw new InvalidArgumentException("Amount cannot be negative");
        }

        if (strlen($currency) !== 3 || !ctype_alpha($currency) || !ctype_upper($currency)) {
            throw new InvalidArgumentException("Currency must be a 3-letter uppercase code");
        }
    }

    /**
     * Check if this money is equal to another
     *
     * @param ValueObject $other The other value object to compare with
     * @return bool True if the money values are equal, false otherwise
     */
    public function equals(ValueObject $other): bool
    {
        return $other instanceof self &&
               $this->amount === $other->amount &&
               $this->currency === $other->currency;
    }

    /**
     * Add another money value to this one
     *
     * @param Money $other The money to add
     * @return Money The result of the addition
     * @throws InvalidArgumentException If the currencies don't match
     */
    public function add(Money $other): self
    {
        if ($this->currency !== $other->currency) {
            throw new InvalidArgumentException("Cannot add different currencies");
        }

        return new self($this->amount + $other->amount, $this->currency);
    }

    /**
     * Subtract another money value from this one
     *
     * @param Money $other The money to subtract
     * @return Money The result of the subtraction
     * @throws InvalidArgumentException If the currencies don't match or result would be negative
     */
    public function subtract(Money $other): self
    {
        if ($this->currency !== $other->currency) {
            throw new InvalidArgumentException("Cannot subtract different currencies");
        }

        $result = $this->amount - $other->amount;
        if ($result < 0) {
            throw new InvalidArgumentException("Result cannot be negative");
        }

        return new self($result, $this->currency);
    }

    /**
     * Get the amount in cents (or smallest currency unit)
     *
     * @return int The amount
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * Get the currency code
     *
     * @return string The currency code
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * Get the formatted amount (e.g., "12.50 USD")
     *
     * @return string The formatted amount
     */
    public function getFormattedAmount(): string
    {
        $amount = $this->amount / 100;
        return number_format($amount, 2) . ' ' . $this->currency;
    }

    /**
     * Convert the money to a string representation
     *
     * @return string The formatted amount
     */
    public function __toString(): string
    {
        return $this->getFormattedAmount();
    }

    /**
     * Convert the money to an array representation
     *
     * @return array The array representation of the money
     */
    public function toArray(): array
    {
        return [
            'amount' => $this->amount,
            'currency' => $this->currency,
            'formatted' => $this->getFormattedAmount(),
        ];
    }
}