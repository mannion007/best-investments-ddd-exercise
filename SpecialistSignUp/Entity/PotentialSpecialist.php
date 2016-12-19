<?php
class PotentialSpecialist
{
    private $id;
    private $contactDetail;
    private $status;

    private function __construct()
    {
        $this->availability = Status::POTENTIALLY_INTERESTED;
    }

    public function giveUp()
    {
        $this->status = Status::NOT_INTERESTED;
        /** Raise an event - Do Andy's team need to know? */
    }
}
