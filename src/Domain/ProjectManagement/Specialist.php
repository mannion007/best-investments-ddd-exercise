<?php

namespace Mannion007\BestInvestments\Domain\ProjectManagement;

class Specialist
{
    private $specialistId;
    private $name;
    private $hourlyRate;

    public function __construct(SpecialistId $specialistId, string $name, Money $hourlyRate)
    {
        $this->specialistId = $specialistId;
        $this->name = $name;
        $this->hourlyRate = $hourlyRate;
    }

}
