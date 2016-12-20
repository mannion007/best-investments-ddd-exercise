<?php

class SpecialistStatus
{
    private $status;

    const APPROVED = 'approved';
    const DISCARDED = 'discarded';

    private function __construct($status)
    {
    }

    public static function approved()
    {
        return new self(self::APPROVED);
    }

    public static function discarded()
    {
        return new self(self::DISCARDED);
    }
}