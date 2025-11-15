<?php

namespace App\ValueObject;

use InvalidArgumentException;

/**
 * Phone number value object with validation
 * 
 * Represents a phone number as an immutable value object.
 */
final class PhoneNumber extends ValueObject
{
    private string $number;
    private string $countryCode;

    /**
     * Private constructor to enforce factory methods
     *
     * @param string $number The phone number digits
     * @param string $countryCode The country code
     */
    private function __construct(string $number, string $countryCode)
    {
        $this->number = $number;
        $this->countryCode = $countryCode;
    }

    /**
     * Create a phone number from a string representation
     *
     * @param string $phoneNumber The phone number string (e.g., "+1-234-567-8900")
     * @return PhoneNumber The phone number value object
     * @throws InvalidArgumentException If the phone number format is invalid
     */
    public static function fromString(string $phoneNumber): self
    {
        // Parse phone number format: +1-234-567-8900 or +12345678900
        if (!preg_match('/^\+(\d{1,3})[\s\-]?([\d\s\-]+)$/', $phoneNumber, $matches)) {
            throw new InvalidArgumentException("Invalid phone number format: {$phoneNumber}");
        }

        $countryCode = $matches[1];
        $number = preg_replace('/[\s\-]/', '', $matches[2]);

        return new self($number, $countryCode);
    }

    /**
     * Create a phone number from its components
     *
     * @param string $number The phone number digits
     * @param string $countryCode The country code
     * @return PhoneNumber The phone number value object
     * @throws InvalidArgumentException If the components are invalid
     */
    public static function fromComponents(string $number, string $countryCode): self
    {
        // Validate components
        if (!ctype_digit($number)) {
            throw new InvalidArgumentException("Number must contain only digits");
        }

        if (!ctype_digit($countryCode) || strlen($countryCode) > 3) {
            throw new InvalidArgumentException("Invalid country code");
        }

        return new self($number, $countryCode);
    }

    /**
     * Check if this phone number is equal to another
     *
     * @param ValueObject $other The other value object to compare with
     * @return bool True if the phone numbers are equal, false otherwise
     */
    public function equals(ValueObject $other): bool
    {
        return $other instanceof self &&
               $this->number === $other->number &&
               $this->countryCode === $other->countryCode;
    }

    /**
     * Get the phone number digits
     *
     * @return string The phone number digits
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * Get the country code
     *
     * @return string The country code
     */
    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    /**
     * Get the international format of the phone number
     *
     * @return string The international format (e.g., "+1 2345678900")
     */
    public function getInternationalFormat(): string
    {
        return "+{$this->countryCode} {$this->number}";
    }

    /**
     * Convert the phone number to a string representation
     *
     * @return string The international format
     */
    public function __toString(): string
    {
        return $this->getInternationalFormat();
    }

    /**
     * Convert the phone number to an array representation
     *
     * @return array The array representation of the phone number
     */
    public function toArray(): array
    {
        return [
            'number' => $this->number,
            'countryCode' => $this->countryCode,
            'international' => $this->getInternationalFormat(),
        ];
    }
}