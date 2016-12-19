<?php
class Specialist
{
    private $id;
    private $availability;

    private function __construct(Availability $availability)
    {
        $this->availability = $availability;
    }

    public function signUp(Availability $availability)
    {
        return new self($availability);
        /** Raise an event that includes the Specialist's availability */
    }
}
