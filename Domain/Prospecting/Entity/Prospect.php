<?php
class Prospect
{
    private $prospectId;
    private $phoneNumber;

    /** @var DateTime[]  */
    private $phoneCalls = [];

    /** @var ProspectStatus */
    private $status;

    private function __construct(ProspectId $prospectId, PhoneNumber $phoneNumber)
    {
        $this->prospectId = $prospectId;
        $this->phoneNumber = $phoneNumber;
        $this->availability = ProspectStatus::IN_PROGRESS;
    }

    public function receive(ProspectId $prospectId, PhoneNumber $phoneNumber)
    {
        return new self($prospectId, $phoneNumber);
    }

    public function chaseUp()
    {
        // Check status here!
        $this->phoneCalls = new DateTime();
    }

    public function notInterested()
    {
        $this->status = ProspectStatus::NOT_REACHABLE;
    }

    public function giveUp()
    {
        $this->status = ProspectStatus::NOT_REACHABLE;
        /** Raise an event - Do Andy's team need to know? */
    }
}
