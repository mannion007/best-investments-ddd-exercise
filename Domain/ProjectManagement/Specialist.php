<?php

namespace Mannion007\BestInvestments\Domain\ProjectManagement;

use Mannion007\BestInvestments\Domain\Invoicing\Money;

class Specialist
{
    private $id;
    private $name;
    private $hourlyRate;

    /** @todo consider the fields here, we need at least background and expertise, "notes" is a bit rubbish */
    private function __construct(SpecialistId $id, string $name, Money $hourlyRate)
    {
        $this->id = new SpecialistId();
        $this->name = $name;
        $this->hourlyRate = $hourlyRate;
    }

    public static function register(SpecialistId $id, string $name, Money $hourlyRate) : Specialist
    {
        return new self($id, $name, $hourlyRate);
    }
}
