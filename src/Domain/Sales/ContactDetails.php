<?php

namespace Mannion007\BestInvestments\Domain\Sales;

class ContactDetails
{
    private $telephone;

    public function __construct(string $telephone)
    {
        $this->telephone = $telephone;
    }

    public static function fromExisting(string $telephone)
    {
        return new self($telephone);
    }

    public function getTelephone(): string
    {
        return $this->telephone;
    }

    public function __toString()
    {
        return (string)$this->telephone;
    }
}
