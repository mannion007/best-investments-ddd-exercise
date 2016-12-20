<?php

class Prospect
{
    private $id;
    private $phoneNumber;

    /** @var DateTime[]  */
    private $phoneCalls = [];

    /** @var ProspectStatus */
    private $status;

    private function __construct(ProspectId $id, PhoneNumber $phoneNumber)
    {
        $this->id = $id;
        $this->phoneNumber = $phoneNumber;
        $this->status = ProspectStatus::IN_PROGRESS;
        /** Raise a 'prospect_received' event */
    }

    public function receive(ProspectId $id, PhoneNumber $phoneNumber)
    {
        return new self($id, $phoneNumber);
    }

    public function chaseUp()
    {
        if(!$this->status->is(ProspectStatus::IN_PROGRESS)) {
            throw new Exception('Prospect does not have "in progress" status');
        }
        $this->phoneCalls[] = new DateTime();
    }

    //register?

    public function declareNotInterested()
    {
        if(!$this->status->is(ProspectStatus::IN_PROGRESS)) {
            throw new Exception('Prospect does not have "in progress" status');
        }
        $this->status = ProspectStatus::NOT_INTERESTED;
        /** Raise a 'prospect_not_interested' event */
    }

    public function giveUp()
    {
        if(!$this->status->is(ProspectStatus::IN_PROGRESS)) {
            throw new Exception('Prospect does not have "in progress" status');
        }
        $this->status = ProspectStatus::NOT_REACHABLE;
        /** Raise a 'prospect_not_reachable' event */
    }
}
