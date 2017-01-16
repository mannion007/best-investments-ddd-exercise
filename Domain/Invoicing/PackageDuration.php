<?php

namespace Mannion007\BestInvestments\Domain\Invoicing;

class PackageDuration
{
    private $months;

    private function __construct(int $months)
    {
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
