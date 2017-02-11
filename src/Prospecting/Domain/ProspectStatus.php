<?php

namespace Mannion007\BestInvestments\Prospecting\Domain;

class ProspectStatus
{
    const REGISTERED = 'registered';
    const IN_PROGRESS = 'in progress';
    const NOT_INTERESTED = 'not interested';
    const NOT_REACHABLE = 'not reachable';

    private $status;

    private function __construct(string $status)
    {
        $this->status = $status;
    }

    public static function registered(): ProspectStatus
    {
        return new self(self::REGISTERED);
    }

    public static function inProgress(): ProspectStatus
    {
        return new self(self::IN_PROGRESS);
    }

    public static function notInterested(): ProspectStatus
    {
        return new self(self::NOT_INTERESTED);
    }

    public static function notReachable(): ProspectStatus
    {
        return new self(self::NOT_REACHABLE);
    }

    public function is($value): bool
    {
        return $value === $this->status;
    }

    public function isNot($value): bool
    {
        return !$this->is($value);
    }
}
