<?php

namespace Mannion007\BestInvestments\Domain\ProjectManagement;

class Specialist
{
    private $specialistId;
    private $name;
    private $hourlyRate;

    /** @todo consider the fields here, we need at least background and expertise, "notes" is a bit rubbish */
    private function __construct(SpecialistId $specialistId, string $name, Money $hourlyRate)
    {
        $this->specialistId = new SpecialistId();
        $this->name = $name;
        $this->hourlyRate = $hourlyRate;
    }

    public static function register(SpecialistId $specialistId, string $name, Money $hourlyRate): Specialist
    {
        return new self($specialistId, $name, $hourlyRate);
    }
}
