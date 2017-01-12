<?php

class Prospect
{
    private $id;
    private $name;
    private $notes;
    private $chaseUps = [];
    /** @var ProspectStatus */
    private $status;

    private function __construct(ProspectId $id, string $name, string $notes)
    {
        $this->id = $id;
        $this->name = $name;
        $this->notes = $notes;
        $this->status = ProspectStatus::inProgress();
        /** Raise a 'prospect_received' event */
    }

    public function receive(ProspectId $id, string $name, string $notes)
    {
        return new self($id, $name, $notes);
    }

    public function chaseUp()
    {
        if ($this->status->isNot(ProspectStatus::IN_PROGRESS)) {
            throw new Exception('Prospect does not have "in progress" status');
        }
        $this->chaseUps[] = new DateTime();
    }

    public function register()
    {
        if ($this->status->isNot(ProspectStatus::IN_PROGRESS)) {
            throw new Exception('Prospect does not have "in progress" status');
        }
        $this->status = ProspectStatus::registered();
        /** Raise a 'prospect_registered' event */
    }

    public function declareNotInterested()
    {
        if ($this->status->isNot(ProspectStatus::IN_PROGRESS)) {
            throw new Exception('Prospect does not have "in progress" status');
        }
        $this->status = ProspectStatus::notInterested();
    }

    public function giveUp()
    {
        if ($this->status->isNot(ProspectStatus::IN_PROGRESS)) {
            throw new Exception('Prospect does not have "in progress" status');
        }
        $this->status = ProspectStatus::notReachable();
    }
}
