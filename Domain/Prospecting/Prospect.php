<?php

namespace Mannion007\BestInvestments\Domain\Prospecting;

use Mannion007\BestInvestments\Event\EventPublisher;

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

        EventPublisher::publish(new ProspectReceived($this->prospectId, $this->name, $this->notes));
    }

    public function receive(ProspectId $prospectId, string $name, string $notes) : Prospect
    {
        return new self($prospectId, $name, $notes);
    }

    public function chaseUp()
    {
        if ($this->status->isNot(ProspectStatus::IN_PROGRESS)) {
            throw new \DomainException('Prospect does not have "in progress" status');
        }
        $this->chaseUps[] = new \DateTime();
    }

    public function register(Money $hourlyRate)
    {
        if ($this->status->isNot(ProspectStatus::IN_PROGRESS)) {
            throw new \DomainException('Prospect does not have "in progress" status');
        }
        $this->hourlyRate = $hourlyRate;
        $this->status = ProspectStatus::registered();

        EventPublisher::publish(new ProspectRegistered($this->prospectId, $this->hourlyRate));
    }

    public function declareNotInterested()
    {
        if ($this->status->isNot(ProspectStatus::IN_PROGRESS)) {
            throw new \DomainException('Prospect does not have "in progress" status');
        }
        $this->status = ProspectStatus::notInterested();
    }

    public function giveUp()
    {
        if ($this->status->isNot(ProspectStatus::IN_PROGRESS)) {
            throw new \DomainException('Prospect does not have "in progress" status');
        }
        $this->status = ProspectStatus::notReachable();
    }
}
