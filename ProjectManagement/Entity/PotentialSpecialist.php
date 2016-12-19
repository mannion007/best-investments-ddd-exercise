<?php
class PotentialSpecialist
{
    private $id;

    private $industry;
    private $background;
    private $expertise;

    /** Assumed name, probably OK? */
    private $contactDetail;

    private $availability;

    /** Assumed there is a rule that all these things are required. */
    private function __construct(Industry $industry, Background $background, Expertise $expertise, ContactDetail $contactDetail)
    {
        $this->id = new SpecialistId();
        $this->industry = $industry;
        $this->background = $background;
        $this->expertise = $expertise;
        $this->contactDetail = $contactDetail;
        /** Raise an event (to let Vlad know there is a new Potential Specialist to "chase up") */
    }

    /** @todo Rename if Vlad provides anything more suitable */
    public static function putOnList(Industry $industry, Background $background, Expertise $expertise, ContactDetail $contactDetail)
    {
        return new self($industry, $background, $expertise, $email, $contactDetail);
    }

    public function signUp(Availability $Availability)
    {
        $this->availability = $availability;
        $this->signedUp = true;
        /** Raise an event */
    }
}