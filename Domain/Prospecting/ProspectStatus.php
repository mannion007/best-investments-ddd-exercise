<?php

namespace Mannion007\BestInvestments\Domain\Prospecting;

class ProspectStatus
{
    const REGISTERED = 'registered';
    const IN_PROGRESS = 'in_progress';
    const NOT_INTERESTED = 'not_interested';
    const NOT_REACHABLE = 'not_reachable';

    private $status;

    private function __construct(string $status)
    {
        $this->status = $status;
    }

    public static function registered() : ProspectStatus
    {
        return new self(self::REGISTERED);
    }

    public static function inProgress() : ProspectStatus
    {
        return new self(self::IN_PROGRESS);
    }

    public static function notInterested() : ProspectStatus
    {
        return new self(self::NOT_INTERESTED);
    }

    public static function notReachable() : ProspectStatus
    {
        return new self(self::NOT_INTERESTED);
    }

    public function is($value) : bool
    {
        return $value === $this->status;
    }

    public function isNot($value) : bool
    {
        return !$this->is($value);
    }
}
