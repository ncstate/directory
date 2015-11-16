<?php

namespace NCState;

use Money\Money;
use Money\Currency;

class Amount
{
    /**
     * @var Money
     */
    private $money;

    /**
     * @param int $cents
     */
    private function __construct($cents)
    {
        if (! is_int($cents) || $cents < 0) {
            throw new \InvalidArgumentException("Must be constructed using a non-negative number denominated in cents");
        }
        $this->money = new Money($cents, new Currency('USD'));
    }

    /**
     * @param int $dollarAmount
     *
     * @return Amount
     */
    public static function fromWholeDollars($dollarAmount)
    {
        return new Amount((int)floor($dollarAmount * 100));
    }

    /**
     * @param int $cents
     *
     * @return Amount
     */
    public static function fromCents($cents)
    {
        return new Amount($cents);
    }

    /**
     * @return int
     */
    public function asWholeDollars()
    {
        return (int) floor($this->money->getAmount()/100);
    }

    /**
     * @return int
     */
    public function asCents()
    {
        return $this->money->getAmount();
    }

    /**
     * Print a formatted version of the amount. Defaults to dollar sign and commas: $99,999 etc
     *
     * @param string $format
     * @return string
     */
    public function format($format="%d")
    {
        return sprintf($format, $this->asWholeDollars());
    }
}