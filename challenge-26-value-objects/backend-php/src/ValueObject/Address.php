<?php

namespace App\ValueObject;

use InvalidArgumentException;

/**
 * Address value object with validation
 * 
 * Represents a postal address as an immutable value object.
 */
final class Address extends ValueObject
{
    private string $street;
    private string $city;
    private string $postalCode;
    private string $country;

    /**
     * Create a new Address value object
     *
     * @param string $street The street address
     * @param string $city The city
     * @param string $postalCode The postal code
     * @param string $country The 2-letter country code
     * @throws InvalidArgumentException If any of the address components are invalid
     */
    public function __construct(string $street, string $city, string $postalCode, string $country)
    {
        $this->validate($street, $city, $postalCode, $country);
        $this->street = $street;
        $this->city = $city;
        $this->postalCode = $postalCode;
        $this->country = $country;
    }

    /**
     * Validate the address components
     *
     * @param string $street The street address to validate
     * @param string $city The city to validate
     * @param string $postalCode The postal code to validate
     * @param string $country The country code to validate
     * @throws InvalidArgumentException If any of the address components are invalid
     */
    private function validate(string $street, string $city, string $postalCode, string $country): void
    {
        if (empty($street)) {
            throw new InvalidArgumentException("Street cannot be empty");
        }

        if (empty($city)) {
            throw new InvalidArgumentException("City cannot be empty");
        }

        if (!preg_match('/^[A-Z0-9\s\-]+$/', $postalCode)) {
            throw new InvalidArgumentException("Invalid postal code format");
        }

        if (strlen($country) !== 2 || !ctype_alpha($country) || !ctype_upper($country)) {
            throw new InvalidArgumentException("Country must be a 2-letter uppercase code");
        }
    }

    /**
     * Check if this address is equal to another
     *
     * @param ValueObject $other The other value object to compare with
     * @return bool True if the addresses are equal, false otherwise
     */
    public function equals(ValueObject $other): bool
    {
        return $other instanceof self &&
               $this->street === $other->street &&
               $this->city === $other->city &&
               $this->postalCode === $other->postalCode &&
               $this->country === $other->country;
    }

    /**
     * Get the street address
     *
     * @return string The street address
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * Get the city
     *
     * @return string The city
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * Get the postal code
     *
     * @return string The postal code
     */
    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    /**
     * Get the country code
     *
     * @return string The country code
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * Convert the address to a string representation
     *
     * @return string The formatted address
     */
    public function __toString(): string
    {
        return "{$this->street}, {$this->city}, {$this->postalCode}, {$this->country}";
    }

    /**
     * Convert the address to an array representation
     *
     * @return array The array representation of the address
     */
    public function toArray(): array
    {
        return [
            'street' => $this->street,
            'city' => $this->city,
            'postalCode' => $this->postalCode,
            'country' => $this->country,
        ];
    }
}