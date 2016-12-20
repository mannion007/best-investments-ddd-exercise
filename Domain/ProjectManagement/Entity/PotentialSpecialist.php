<?php

class PotentialSpecialist
{
    private $id;
    private $phoneNumber;

    private function __construct(
        PhoneNumber $phoneNumber
    ) {
        $this->id = new SpecialistId();
        $this->phoneNumber = $phoneNumber;
        /** Raise a 'potential_specialist_put_on_list' event */
    }

    public static function putOnList(
        PhoneNumber $phoneNumber
    ) {
        return new self($phoneNumber);
    }
}
