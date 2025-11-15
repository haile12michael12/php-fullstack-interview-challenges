<?php

namespace App\ValueObject;

use InvalidArgumentException;

/**
 * Email value object with validation
 * 
 * Represents a valid email address as an immutable value object.
 */
final class Email extends ValueObject
{
    private string $email;

    /**
     * Create a new Email value object
     *
     * @param string $email The email address
     * @throws InvalidArgumentException If the email is invalid
     */
    public function __construct(string $email)
    {
        $this->validate($email);
        $this->email = $email;
    }

    /**
     * Validate the email address
     *
     * @param string $email The email address to validate
     * @throws InvalidArgumentException If the email is invalid
     */
    private function validate(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Invalid email format: {$email}");
        }

        if (strlen($email) > 255) {
            throw new InvalidArgumentException("Email too long: {$email}");
        }
    }

    /**
     * Check if this email is equal to another
     *
     * @param ValueObject $other The other value object to compare with
     * @return bool True if the emails are equal, false otherwise
     */
    public function equals(ValueObject $other): bool
    {
        return $other instanceof self && $this->email === $other->email;
    }

    /**
     * Get the email address
     *
     * @return string The email address
     */
    public function getValue(): string
    {
        return $this->email;
    }

    /**
     * Convert the email to a string representation
     *
     * @return string The email address
     */
    public function __toString(): string
    {
        return $this->email;
    }

    /**
     * Convert the email to an array representation
     *
     * @return array The array representation of the email
     */
    public function toArray(): array
    {
        return ['email' => $this->email];
    }
}