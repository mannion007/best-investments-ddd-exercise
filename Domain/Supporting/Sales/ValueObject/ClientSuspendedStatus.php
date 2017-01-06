<?php

class ClientStatus
{
    const ACTIVE = 'active';
    const SUSPENDED = 'suspended';

    private $status;

    private function __construct(string $status)
    {
        $this->status = $status;
    }

    public static function active()
    {
        return new self(self::ACTIVE);
    }

    public static function suspended()
    {
        return new self(self::SUSPENDED);
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
