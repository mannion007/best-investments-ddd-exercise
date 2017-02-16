<?php

namespace Mannion007\BestInvestments\Prospecting\Domain;

use Mannion007\BestInvestments\EventPublisher\EventPublisher;

class Prospect
{
    private $prospectId;
    private $name;
    private $notes;
    private $chaseUps = [];
    private $hourlyRate;
    private $status;

    private function __construct(ProspectId $prospectId, string $name, string $notes)
    {
        $this->prospectId = $prospectId;
        $this->name = $name;
        $this->notes = $notes;
        $this->status = ProspectStatus::inProgress();
        EventPublisher::publish(new ProspectReceivedEvent((string)$this->prospectId, $this->name, $this->notes));
    }

    public static function receive(ProspectId $prospectId, string $name, string $notes): Prospect
    {
        return new self($prospectId, $name, $notes);
    }

    public function chaseUp()
    {
        if ($this->status->isNot(ProspectStatus::IN_PROGRESS)) {
            throw new \Exception('Cannot chase up prospect that is not In Progress');
        }
        $this->chaseUps[] = new \DateTime();
    }

    public function register(Money $hourlyRate)
    {
        if ($this->status->isNot(ProspectStatus::IN_PROGRESS)) {
            throw new \Exception('Cannot register Prospect that is not In Progress');
        }
        $this->hourlyRate = $hourlyRate;
        $this->status = ProspectStatus::registered();
        EventPublisher::publish(new ProspectRegisteredEvent((string)$this->prospectId, (string)$this->hourlyRate));
    }

    public function declareNotInterested()
    {
        if ($this->status->isNot(ProspectStatus::IN_PROGRESS)) {
            throw new \Exception('Cannot declare not interested for Prospect that is not In Progress');
        }
        $this->status = ProspectStatus::notInterested();
        EventPublisher::publish(new ProspectNotInterestedEvent($this->prospectId));
    }

    public function giveUp()
    {
        if ($this->status->isNot(ProspectStatus::IN_PROGRESS)) {
            throw new \Exception('Cannot give up on Prospect that is not In Progress');
        }
        $this->status = ProspectStatus::notReachable();
        EventPublisher::publish(new ProspectGivenUpOnEvent($this->prospectId));
    }

    public function getProspectId(): ProspectId
    {
        return $this->prospectId;
    }
}
