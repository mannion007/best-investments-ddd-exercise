<?php

namespace Mannion007\BestInvestments\Sales\Domain;

class ClientStatus
{
    const ACTIVE = 'active';
    const SUSPENDED = 'suspended';

    private $status;

    private function __construct(string $status)
    {
        $this->status = $status;
    }

    public static function active(): ClientStatus
    {
        return new self(self::ACTIVE);
    }

    public static function suspended(): ClientStatus
    {
        return new self(self::SUSPENDED);
    }

    public function is(string $status): bool
    {
        return $status === $this->status;
    }

    public function isNot(string $status): bool
    {
        return !$this->is($status);
    }
}
