<?php

namespace Mannion007\BestInvestments\ProjectManagement\Domain;

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

    public function add(Money $other)
    {
        return new static($this->amount + $other->getAmount());
    }

    public function subtract(Money $other)
    {
        return new static($this->amount - $other->getAmount());
    }

    public function isMoreThan(Money $other)
    {
        return $this->amount > $other;
    }

    public function isLessThan(Money $other)
    {
        return $this->amount < $other;
    }

    public function __toString()
    {
        return (string)$this->amount;
    }
}
