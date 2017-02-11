<?php

namespace Mannion007\BestInvestments\Invoicing\Domain;

class Money
{
    private $amount;

    public function __construct(int $amount)
    {
        $this->amount = $amount;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function add(Money $other): Money
    {
        return new static($this->amount + $other->getAmount());
    }

    public function subtract(Money $other): Money
    {
        return new static($this->amount - $other->getAmount());
    }

    public function isMoreThan(Money $other): bool
    {
        return $this->amount > $other;
    }

    public function isLessThan(Money $other): bool
    {
        return $this->amount < $other;
    }

    public function __toString()
    {
        return (string)$this->amount;
    }
}
