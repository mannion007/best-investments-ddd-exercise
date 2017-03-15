<?php

namespace Mannion007\BestInvestments\Invoicing\Domain;

class PackageLength
{
    private $validLengths = [6,12];

    /** @var int */
    private $months;

    private function __construct(int $months)
    {
        if (!in_array($months, $this->validLengths)) {
            throw new \InvalidArgumentException('Invalid Package length');
        }
        $this->months = $months;
    }

    public static function sixMonths(): PackageLength
    {
        return new self(6);
    }

    public static function twelveMonths(): PackageLength
    {
        return new self(12);
    }

    public static function fromExisting(string $existing): PackageLength
    {
        return new self((int)$existing);
    }

    public function __toString()
    {
        return (string)$this->months;
    }
}
