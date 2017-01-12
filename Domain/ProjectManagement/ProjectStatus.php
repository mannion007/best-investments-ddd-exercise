<?php

class ProjectStatus
{
    const DRAFT = 'draft';
    const ACTIVE = 'active';
    const ON_HOLD = 'on_hold';
    const CLOSED = 'closed';

    private $status;

    private function __construct($status)
    {
        $this->status = $status;
    }

    public static function active()
    {
        return new self(self::ACTIVE);
    }

    public static function draft()
    {
        return new self(self::DRAFT);
    }

    public static function onHold()
    {
        return new self(self::ON_HOLD);
    }

    public static function closed()
    {
        return new self(self::CLOSED);
    }

    public function is($status)
    {
        return $status === $this->status;
    }

    public function isNot($status)
    {
        return !$this->is($status);
    }
}
