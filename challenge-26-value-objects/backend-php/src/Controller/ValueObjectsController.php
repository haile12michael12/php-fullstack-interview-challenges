<?php

namespace App\Controller;

use App\Http\Request;
use App\Http\Response;
use App\ValueObject\Email;
use App\ValueObject\Money;
use App\ValueObject\Address;
use App\ValueObject\PhoneNumber;
use App\ValueObject\DateTimeRange;
use DateTimeImmutable;
use InvalidArgumentException;

class ValueObjectsController
{
    /**
     * Demonstrate email value object
     */
    public function emailDemo(Request $request): Response
    {
        try {
            $email = new Email('user@example.com');
            $email2 = new Email('user@example.com');
            $email3 = new Email('other@example.com');
            
            $data = [
                'email1' => $email->toArray(),
                'email2' => $email2->toArray(),
                'email3' => $email3->toArray(),
                'equal1and2' => $email->equals($email2),
                'equal1and3' => $email->equals($email3),
                'stringRepresentation' => (string) $email
            ];
            
            return new Response([
                'message' => 'Email value object demo',
                'data' => $data
            ]);
        } catch (InvalidArgumentException $e) {
            return new Response([
                'error' => 'Invalid email',
                'message' => $e->getMessage()
            ], 400);
        }
    }
    
    /**
     * Demonstrate money value object
     */
    public function moneyDemo(Request $request): Response
    {
        try {
            $money1 = new Money(10000, 'USD'); // $100.00
            $money2 = new Money(5000, 'USD');  // $50.00
            $money3 = new Money(7500, 'USD');  // $75.00
            
            $added = $money1->add($money2);
            $subtracted = $money1->subtract($money3);
            
            $data = [
                'money1' => $money1->toArray(),
                'money2' => $money2->toArray(),
                'money3' => $money3->toArray(),
                'added' => $added->toArray(),
                'subtracted' => $subtracted->toArray(),
                'equal1and2' => $money1->equals($money2)
            ];
            
            return new Response([
                'message' => 'Money value object demo',
                'data' => $data
            ]);
        } catch (InvalidArgumentException $e) {
            return new Response([
                'error' => 'Invalid money',
                'message' => $e->getMessage()
            ], 400);
        }
    }
    
    /**
     * Demonstrate address value object
     */
    public function addressDemo(Request $request): Response
    {
        try {
            $address1 = new Address('123 Main St', 'New York', '10001', 'US');
            $address2 = new Address('123 Main St', 'New York', '10001', 'US');
            $address3 = new Address('456 Oak Ave', 'Los Angeles', '90210', 'US');
            
            $data = [
                'address1' => $address1->toArray(),
                'address2' => $address2->toArray(),
                'address3' => $address3->toArray(),
                'equal1and2' => $address1->equals($address2),
                'equal1and3' => $address1->equals($address3),
                'stringRepresentation' => (string) $address1
            ];
            
            return new Response([
                'message' => 'Address value object demo',
                'data' => $data
            ]);
        } catch (InvalidArgumentException $e) {
            return new Response([
                'error' => 'Invalid address',
                'message' => $e->getMessage()
            ], 400);
        }
    }
    
    /**
     * Demonstrate phone number value object
     */
    public function phoneNumberDemo(Request $request): Response
    {
        try {
            $phone1 = PhoneNumber::fromString('+1-234-567-8900');
            $phone2 = PhoneNumber::fromComponents('2345678900', '1');
            $phone3 = PhoneNumber::fromString('+44-123-456-7890');
            
            $data = [
                'phone1' => $phone1->toArray(),
                'phone2' => $phone2->toArray(),
                'phone3' => $phone3->toArray(),
                'equal1and2' => $phone1->equals($phone2),
                'equal1and3' => $phone1->equals($phone3),
                'stringRepresentation' => (string) $phone1
            ];
            
            return new Response([
                'message' => 'Phone number value object demo',
                'data' => $data
            ]);
        } catch (InvalidArgumentException $e) {
            return new Response([
                'error' => 'Invalid phone number',
                'message' => $e->getMessage()
            ], 400);
        }
    }
    
    /**
     * Demonstrate datetime range value object
     */
    public function dateTimeRangeDemo(Request $request): Response
    {
        try {
            $start1 = new DateTimeImmutable('2023-01-01 10:00:00');
            $end1 = new DateTimeImmutable('2023-01-01 12:00:00');
            $range1 = new DateTimeRange($start1, $end1);
            
            $start2 = new DateTimeImmutable('2023-01-01 11:00:00');
            $end2 = new DateTimeImmutable('2023-01-01 13:00:00');
            $range2 = new DateTimeRange($start2, $end2);
            
            $testDate = new DateTimeImmutable('2023-01-01 11:30:00');
            
            $data = [
                'range1' => $range1->toArray(),
                'range2' => $range2->toArray(),
                'equal' => $range1->equals($range2),
                'contains' => $range1->contains($testDate),
                'overlaps' => $range1->overlaps($range2),
                'stringRepresentation' => (string) $range1
            ];
            
            return new Response([
                'message' => 'DateTime range value object demo',
                'data' => $data
            ]);
        } catch (InvalidArgumentException $e) {
            return new Response([
                'error' => 'Invalid date range',
                'message' => $e->getMessage()
            ], 400);
        }
    }
    
    /**
     * Get all available value object types
     */
    public function getValueObjectTypes(Request $request): Response
    {
        $types = [
            'Email',
            'Money',
            'Address',
            'PhoneNumber',
            'DateTimeRange'
        ];
        
        return new Response([
            'message' => 'Available value object types',
            'data' => $types
        ]);
    }
}