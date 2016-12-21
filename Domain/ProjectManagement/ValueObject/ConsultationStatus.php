<?php

class ConsultationStatus
{
    const OPEN = 'open';
    const DISCARDED = 'discarded';
    const CONFIRMED = 'confirmed';

    private $status;

    private function __construct(string $status)
    {
        $this->status = $status;
    }

    public static function open()
    {
        return new self(self::OPEN);
    }

    public static function discarded()
    {
        return new self(self::DISCARDED);
    }

    public static function confirmed()
    {
        return new self(self::CONFIRMED);
    }

    public function is($value)
    {
        return $value === $this->status;
    }
}