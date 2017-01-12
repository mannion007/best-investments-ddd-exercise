<?php

namespace Mannion007\BestInvestments\ProjectManagement;

class Specialist
{
    private $id;
    private $name;

    /** @todo consider the fields here, we need at least background and expertise, "notes" is a bit rubbish */
    private function __construct(SpecialistId $id, string $name)
    {
        $this->id = new SpecialistId();
        $this->name = $name;
    }

    public static function register(SpecialistId $id, string $name) : Specialist
    {
        return new self($id, $name);
    }
}
