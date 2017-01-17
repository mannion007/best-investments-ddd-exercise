<?php

namespace Mannion007\BestInvestments\Domain\Sales;

class PackageDuration
{
    const VALID_DURATIONS = [6,12];

    private $months;

    public function __construct(int $months)
    {
        if (!in_array($months, self::VALID_DURATIONS)) {
            throw new \DomainException('Invalid number of months for Package Duration');
        }
        $this->months = $months;
    }

    public static function sixMonths()
    {
        return new self(6);
    }

    public static function twelveMonths()
    {
        return new self(12);
    }

    public function __toString()
    {
        return (string)$this->months;
    }
}
