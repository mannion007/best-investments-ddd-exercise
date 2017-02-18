<?php

namespace Mannion007\BestInvestments\ProjectManagement\Domain;

class Money
{
    private $amount;
    private $currency;

    public function __construct(int $amount, Currency $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function add(Money $other): Money
    {
        if ($other->getCurrency()->isNot($this->getCurrency())) {
            throw new \Exception('Cannot add because currencies do not match');
        }
        return new static($this->amount + $other->getAmount(), $this->currency);
    }

    public function subtract(Money $other): Money
    {
        if ($other->getCurrency()->isNot($this->getCurrency())) {
            throw new \Exception('Cannot subtract because currencies do not match');
        }
        return new static($this->amount - $other->getAmount(), $this->currency);
    }

    public function isMoreThan(Money $other): bool
    {
        return $this->compareWith($other) === 1;
    }

    public function isLessThan(Money $other): bool
    {
        return $this->compareWith($other) < 1;
    }

    public function isEqualTo(Money $other): bool
    {
        return $this->compareWith($other) === 0;
    }

    private function compareWith(Money $other): int
    {
        if ($other->getCurrency()->isNot($this->getCurrency())) {
            throw new \Exception('Cannot compare because currencies do not match');
        }
        return $this->getAmount() <=> $other->getAmount();
    }

    public function __toString()
    {
        return (string)$this->amount;
    }
}
