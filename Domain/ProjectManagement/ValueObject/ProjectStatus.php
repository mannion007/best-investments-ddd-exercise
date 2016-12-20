<?php

class ProjectStatus
{
    const DRAFT = 'draft';
    const ACTIVE = 'active';
    const ENDED = 'ended';

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

    public function is($value)
    {
        return $value === $this->status;
    }
}