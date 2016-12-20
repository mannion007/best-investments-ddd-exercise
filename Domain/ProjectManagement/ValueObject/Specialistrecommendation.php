<?php

class SpecialistRecommendation
{
    const UNVETTED = 'unvetted';
    const APROVED = 'approved';
    const DISCARDED = 'discarded';

    private $status;

    private function __construct(string $status)
    {
        $this->status = $status;
    }

    public static function unvetted()
    {
        return new self(self::UNVETTED);
    }

    public static function approved()
    {
        return new self(self::APPROVED);
    }

    public static function discarded()
    {
        return new self(self::DISCARDED);
    }

    public function is($value)
    {
        return $value === $this->status;
    }
}