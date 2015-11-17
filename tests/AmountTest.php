<?php

use NCState\Amount;

class AmountTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_can_be_constructed_from_a_whole_dollar_amount()
    {
        $amount = Amount::fromWholeDollars(100);
        $this->assertInstanceOf(Amount::class, $amount);
        $this->assertEquals(100, $amount->asWholeDollars());
    }

    /** @test */
    public function it_should_be_constructed_from_cents()
    {
        $amount = Amount::fromCents(10000);
        $this->assertEquals(100, $amount->asWholeDollars());
    }

    /** @test */
    public function it_should_throw_an_exception_when_cast_to_a_string()
    {
        $amount = Amount::fromCents(50000);
        $this->setExpectedException('PHPUnit_Framework_Exception');

        $stringAmount = (string) $amount;
    }

    /** @test */
    public function it_should_round_correctly_when_converting_cents_to_dollars()
    {
        $amount = Amount::fromCents(54399);
        $amountInDollars = $amount->asWholeDollars();

        $this->assertEquals(543, $amountInDollars, 'Amounts should be rounded down always');
    }

    /** @test */
    public function it_should_give_the_amount_in_dollars()
    {
        $amount = Amount::fromCents(54321);
        $amountInDollars = $amount->asWholeDollars();

        $this->assertEquals(543, $amountInDollars, 'Amount should be able to report its value denominated in whole dollars');
    }

    /** @test */
    public function it_should_give_the_amount_in_cents()
    {
        $amount = Amount::fromWholeDollars(54321);
        $amountInCents = $amount->asCents();

        $this->assertEquals(5432100, $amountInCents, 'Amount should be able to report its value denominated in cents');
    }
    
}