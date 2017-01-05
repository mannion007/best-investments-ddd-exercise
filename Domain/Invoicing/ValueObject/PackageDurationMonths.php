<?php

class PackageDurationMonths
{
    const SIX = 6;
    const TWELVE = 12;

    private $months;

    private function __construct(int $months)
    {
        $this->months = $months;
    }

    public static function six()
    {
        return new self(self::SIX);
    }

    public static function twelve()
    {
        return new self(self::TWELVE);
    }

    public function __toString()
    {
        return (string)$this->months;
    }
}
