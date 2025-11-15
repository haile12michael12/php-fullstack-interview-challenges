<?php

namespace Tests\Unit\ValueObject;

use PHPUnit\Framework\TestCase;
use App\ValueObject\Money;
use InvalidArgumentException;

class MoneyTest extends TestCase
{
    public function testMoneyCanBeCreatedWithValidAmountAndCurrency()
    {
        $money = new Money(10000, 'USD');
        $this->assertInstanceOf(Money::class, $money);
        $this->assertEquals(10000, $money->getAmount());
        $this->assertEquals('USD', $money->getCurrency());
        $this->assertEquals('100.00 USD', (string) $money);
    }

    public function testMoneyCannotBeCreatedWithNegativeAmount()
    {
        $this->expectException(InvalidArgumentException::class);
        new Money(-100, 'USD');
    }

    public function testMoneyCannotBeCreatedWithInvalidCurrency()
    {
        $this->expectException(InvalidArgumentException::class);
        new Money(10000, 'INVALID');
    }

    public function testMoneyEquality()
    {
        $money1 = new Money(10000, 'USD');
        $money2 = new Money(10000, 'USD');
        $money3 = new Money(5000, 'USD');
        $money4 = new Money(10000, 'EUR');

        $this->assertTrue($money1->equals($money2));
        $this->assertFalse($money1->equals($money3));
        $this->assertFalse($money1->equals($money4));
    }

    public function testMoneyCanBeConvertedToArray()
    {
        $money = new Money(10000, 'USD');
        $expected = [
            'amount' => 10000,
            'currency' => 'USD',
            'formatted' => '100.00 USD'
        ];
        $this->assertEquals($expected, $money->toArray());
    }

    public function testMoneyCanBeAdded()
    {
        $money1 = new Money(10000, 'USD');
        $money2 = new Money(5000, 'USD');
        $result = $money1->add($money2);

        $this->assertInstanceOf(Money::class, $result);
        $this->assertEquals(15000, $result->getAmount());
        $this->assertEquals('USD', $result->getCurrency());
    }

    public function testMoneyCannotBeAddedWithDifferentCurrencies()
    {
        $this->expectException(InvalidArgumentException::class);
        $money1 = new Money(10000, 'USD');
        $money2 = new Money(5000, 'EUR');
        $money1->add($money2);
    }

    public function testMoneyCanBeSubtracted()
    {
        $money1 = new Money(10000, 'USD');
        $money2 = new Money(5000, 'USD');
        $result = $money1->subtract($money2);

        $this->assertInstanceOf(Money::class, $result);
        $this->assertEquals(5000, $result->getAmount());
        $this->assertEquals('USD', $result->getCurrency());
    }

    public function testMoneyCannotBeSubtractedWithDifferentCurrencies()
    {
        $this->expectException(InvalidArgumentException::class);
        $money1 = new Money(10000, 'USD');
        $money2 = new Money(5000, 'EUR');
        $money1->subtract($money2);
    }

    public function testMoneyCannotBeSubtractedToNegativeResult()
    {
        $this->expectException(InvalidArgumentException::class);
        $money1 = new Money(5000, 'USD');
        $money2 = new Money(10000, 'USD');
        $money1->subtract($money2);
    }
}