<?php

namespace Mannion007\BestInvestments\Prospecting\Domain;

use Mannion007\Interfaces\Event\EventInterface;

class ProspectNotInterestedEvent implements EventInterface
{
    const EVENT_NAME = 'prospect_not_interested';

    private $prospectId;
    private $occurredAt;

    public function __construct(string $prospectId, \DateTimeInterface $occurredAt = null)
    {
        $this->prospectId = $prospectId;
        $this->occurredAt = is_null($occurredAt) ? new \DateTime() : $occurredAt;
    }

    public function getProspectId(): string
    {
        return $this->prospectId;
    }

    public function getEventName(): string
    {
        return self::EVENT_NAME;
    }

    public function getOccurredAt(): \DateTimeInterface
    {
        return $this->occurredAt;
    }

    public function getPayload(): array
    {
        return ['prospect_id' => $this->prospectId];
    }

    public static function fromPayload(array $payload): ProspectNotInterestedEvent
    {
        return new self($payload['prospect_id']);
    }
}
