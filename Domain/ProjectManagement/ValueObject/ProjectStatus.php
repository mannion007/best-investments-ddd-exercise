<?php

class ProjectStatus
{
    private $status;

    const DRAFT = 'draft';
    const ACTIVE = 'active';
    const ENDED = 'ended';

    private function __construct($status)
    {
    }

    public static function active()
    {
        return new self(self::ACTIVE);
    }

    public static function draft()
    {
        return new self(self::DRAFT);
    }
}