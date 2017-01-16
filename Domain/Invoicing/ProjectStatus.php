<?php

namespace Mannion007\BestInvestments\Domain\Invoicing;

class ProjectStatus
{
    const ACTIVE = 'active';
    const ENDED = 'ended';

    private $status;

    private function __construct($status)
    {
        $this->status = $status;
    }

    public static function active() : ProjectStatus
    {
        return new self(self::ACTIVE);
    }

    public static function ended() : ProjectStatus
    {
        return new self(self::ENDED);
    }

    public function is($status) : bool
    {
        return $status === $this->status;
    }

    public function isNot($status) : bool
    {
        return !$this->is($status);
    }
}
