<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\ValueObject\Email;
use App\ValueObject\Money;
use App\ValueObject\Address;
use App\ValueObject\PhoneNumber;
use App\ValueObject\DateTimeRange;
use DateTimeImmutable;
use InvalidArgumentException;

echo "Testing Value Objects Implementation\n";
echo "==================================\n\n";

// Test Email
echo "1. Testing Email Value Object:\n";
try {
    $email1 = new Email('user@example.com');
    $email2 = new Email('user@example.com');
    $email3 = new Email('other@example.com');
    
    echo "  Email 1: " . $email1 . "\n";
    echo "  Email 2: " . $email2 . "\n";
    echo "  Email 3: " . $email3 . "\n";
    echo "  Email 1 equals Email 2: " . ($email1->equals($email2) ? 'true' : 'false') . "\n";
    echo "  Email 1 equals Email 3: " . ($email1->equals($email3) ? 'true' : 'false') . "\n";
    echo "  Email 1 as array: " . json_encode($email1->toArray()) . "\n\n";
} catch (InvalidArgumentException $e) {
    echo "  Error: " . $e->getMessage() . "\n\n";
}

// Test Money
echo "2. Testing Money Value Object:\n";
try {
    $money1 = new Money(10000, 'USD'); // $100.00
    $money2 = new Money(5000, 'USD');  // $50.00
    
    echo "  Money 1: " . $money1 . "\n";
    echo "  Money 2: " . $money2 . "\n";
    echo "  Money 1 equals Money 2: " . ($money1->equals($money2) ? 'true' : 'false') . "\n";
    
    $added = $money1->add($money2);
    echo "  Money 1 + Money 2: " . $added . "\n";
    
    $subtracted = $money1->subtract($money2);
    echo "  Money 1 - Money 2: " . $subtracted . "\n";
    
    echo "  Money 1 as array: " . json_encode($money1->toArray()) . "\n\n";
} catch (InvalidArgumentException $e) {
    echo "  Error: " . $e->getMessage() . "\n\n";
}

// Test Address
echo "3. Testing Address Value Object:\n";
try {
    $address1 = new Address('123 Main St', 'New York', '10001', 'US');
    $address2 = new Address('123 Main St', 'New York', '10001', 'US');
    $address3 = new Address('456 Oak Ave', 'Los Angeles', '90210', 'US');
    
    echo "  Address 1: " . $address1 . "\n";
    echo "  Address 2: " . $address2 . "\n";
    echo "  Address 3: " . $address3 . "\n";
    echo "  Address 1 equals Address 2: " . ($address1->equals($address2) ? 'true' : 'false') . "\n";
    echo "  Address 1 equals Address 3: " . ($address1->equals($address3) ? 'true' : 'false') . "\n";
    echo "  Address 1 as array: " . json_encode($address1->toArray()) . "\n\n";
} catch (InvalidArgumentException $e) {
    echo "  Error: " . $e->getMessage() . "\n\n";
}

// Test PhoneNumber
echo "4. Testing PhoneNumber Value Object:\n";
try {
    $phone1 = PhoneNumber::fromString('+1-234-567-8900');
    $phone2 = PhoneNumber::fromComponents('2345678900', '1');
    $phone3 = PhoneNumber::fromString('+44-123-456-7890');
    
    echo "  Phone 1: " . $phone1 . "\n";
    echo "  Phone 2: " . $phone2 . "\n";
    echo "  Phone 3: " . $phone3 . "\n";
    echo "  Phone 1 equals Phone 2: " . ($phone1->equals($phone2) ? 'true' : 'false') . "\n";
    echo "  Phone 1 equals Phone 3: " . ($phone1->equals($phone3) ? 'true' : 'false') . "\n";
    echo "  Phone 1 as array: " . json_encode($phone1->toArray()) . "\n\n";
} catch (InvalidArgumentException $e) {
    echo "  Error: " . $e->getMessage() . "\n\n";
}

// Test DateTimeRange
echo "5. Testing DateTimeRange Value Object:\n";
try {
    $start1 = new DateTimeImmutable('2023-01-01 10:00:00');
    $end1 = new DateTimeImmutable('2023-01-01 12:00:00');
    $range1 = new DateTimeRange($start1, $end1);
    
    $start2 = new DateTimeImmutable('2023-01-01 11:00:00');
    $end2 = new DateTimeImmutable('2023-01-01 13:00:00');
    $range2 = new DateTimeRange($start2, $end2);
    
    $testDate = new DateTimeImmutable('2023-01-01 11:30:00');
    
    echo "  Range 1: " . $range1 . "\n";
    echo "  Range 2: " . $range2 . "\n";
    echo "  Range 1 equals Range 2: " . ($range1->equals($range2) ? 'true' : 'false') . "\n";
    echo "  Range 1 contains " . $testDate->format('Y-m-d H:i:s') . ": " . ($range1->contains($testDate) ? 'true' : 'false') . "\n";
    echo "  Range 1 overlaps Range 2: " . ($range1->overlaps($range2) ? 'true' : 'false') . "\n";
    echo "  Range 1 as array: " . json_encode($range1->toArray()) . "\n\n";
} catch (InvalidArgumentException $e) {
    echo "  Error: " . $e->getMessage() . "\n\n";
}

echo "Value Objects testing completed!\n";