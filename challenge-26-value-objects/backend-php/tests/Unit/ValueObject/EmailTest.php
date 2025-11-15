<?php

namespace Tests\Unit\ValueObject;

use PHPUnit\Framework\TestCase;
use App\ValueObject\Email;
use InvalidArgumentException;

class EmailTest extends TestCase
{
    public function testEmailCanBeCreatedWithValidEmail()
    {
        $email = new Email('test@example.com');
        $this->assertInstanceOf(Email::class, $email);
        $this->assertEquals('test@example.com', $email->getValue());
    }

    public function testEmailCannotBeCreatedWithInvalidEmail()
    {
        $this->expectException(InvalidArgumentException::class);
        new Email('invalid-email');
    }

    public function testEmailCannotBeCreatedWithTooLongEmail()
    {
        $this->expectException(InvalidArgumentException::class);
        $longEmail = str_repeat('a', 250) . '@example.com'; // More than 255 characters
        new Email($longEmail);
    }

    public function testEmailEquality()
    {
        $email1 = new Email('test@example.com');
        $email2 = new Email('test@example.com');
        $email3 = new Email('other@example.com');

        $this->assertTrue($email1->equals($email2));
        $this->assertFalse($email1->equals($email3));
    }

    public function testEmailCanBeConvertedToString()
    {
        $email = new Email('test@example.com');
        $this->assertEquals('test@example.com', (string) $email);
    }

    public function testEmailCanBeConvertedToArray()
    {
        $email = new Email('test@example.com');
        $expected = ['email' => 'test@example.com'];
        $this->assertEquals($expected, $email->toArray());
    }
}