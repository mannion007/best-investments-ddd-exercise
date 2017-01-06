<?php

class PackageStatus
{
    const ACTIVE = 'active';
    const INACTIVE = 'inactive';
    const EXPIRED = 'expired';
    const CLOSED = 'closed';

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

    public static function expired()
    {
        return new self(self::EXPIRED);
    }

    public static function closed()
    {
        return new self(self::CLOSED);
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
