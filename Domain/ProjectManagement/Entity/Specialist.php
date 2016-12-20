<?php

class Specialist
{
    private $id;
    private $availability;

    private function __construct(SpecialistId $id, Availability $availability)
    {
        $this->id = new SpecialistId();
        $this->availability = $availability;
    }

    public function joinUp(SpecialistId $id, Availability $availability)
    {
        $this->availability = $availability;
        /** Raise a 'specialist_joined_up' event */
    }
}