<?php

namespace Mannion007\BestInvestments\ProjectManagement;

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

    public static function unvetted() : SpecialistRecommendation
    {
        return new self(self::UNVETTED);
    }

    public static function approved() : SpecialistRecommendation
    {
        return new self(self::APPROVED);
    }

    public static function discarded() : SpecialistRecommendation
    {
        return new self(self::DISCARDED);
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