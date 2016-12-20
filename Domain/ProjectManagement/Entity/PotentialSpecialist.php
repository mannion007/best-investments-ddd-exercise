<?php
class PotentialSpecialist
{
    private $id;
    private $industry;
    private $background;
    private $expertise;
    private $phoneNumber;

    private function __construct(
        Industry $industry,
        Background $background,
        Expertise $expertise,
        PhoneNumber $phoneNumber
    ) {
        $this->id = new SpecialistId();
        $this->industry = $industry;
        $this->background = $background;
        $this->expertise = $expertise;
        $this->phoneNumber = $phoneNumber;
        /** Raise an event (to let Vlad know there is a new Potential Specialist to "chase up") */
    }

    public static function putOnList(
        Industry $industry,
        Background $background,
        Expertise $expertise,
        PhoneNumber $phoneNumber
    ) {
        return new self($industry, $background, $expertise, $phoneNumber);
    }
}