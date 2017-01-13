<?php

namespace Mannion007\BestInvestments\Domain\Sales;

class PackageStatus
{
    const ACTIVE = 'active';
    const INACTIVE = 'inactive';

    private $status;

    private function __construct(string $status)
    {
        $this->status = $status;
    }

    public static function active()
    {
        return new self(self::ACTIVE);
    }

    public static function inactive()
    {
        return new self(self::INACTIVE);
    }

    public function is(string $status)
    {
        return $status === $this->status;
    }

    public function isNot(string $status)
    {
        return !$this->is($status);
    }
}
