<?php

namespace Mannion007\BestInvestments\ProjectManagement\Domain;

class Specialist
{
    private $specialistId;
    private $name;
    private $hourlyRate;

    public function __construct(SpecialistId $specialistId, string $name, HourlyRate $hourlyRate)
    {
        $this->specialistId = $specialistId;
        $this->name = $name;
        $this->hourlyRate = $hourlyRate;
    }

    public function getSpecialistId(): SpecialistId
    {
        return $this->specialistId;
    }
}
