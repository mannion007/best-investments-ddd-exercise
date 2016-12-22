<?php
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

    public static function registered()
    {
        return new self(self::REGISTERED);
    }

    public static function inProgress()
    {
        return new self(self::IN_PROGRESS);
    }

    public static function notInterested()
    {
        return new self(self::NOT_INTERESTED);
    }

    public static function notReachable()
    {
        return new self(self::NOT_INTERESTED);
    }

    public function is($value)
    {
        return $value === $this->status;
    }

    public function isNot($value)
    {
        return !$this->is($value);
    }

}