<?php

namespace Mannion007\BestInvestments\Domain\Invoicing;

class PackageDuration
{
    const SIX = 6;
    const TWELVE = 12;

    private $months;

    private function __construct(int $months)
    {
        $this->months = $months;
    }

    public static function sixMonths()
    {
        return new self(self::SIX);
    }

    public static function twelveMonths()
    {
        return new self(self::TWELVE);
    }

    public function __toString()
    {
        return (string)$this->months;
    }
}
