<?php

namespace Mannion007\BestInvestments\Domain\Prospecting;

use Mannion007\BestInvestments\Event\EventInterface;

class ProspectNotInterestedEvent implements EventInterface
{
    const EVENT_NAME = 'prospect_not_interested';

    private $prospectId;
    private $occurredAt;

    public function __construct($prospectId, \DateTimeInterface $occurredAt = null)
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
        return [
            'prospect_id' => $this->prospectId
        ];
    }

    public static function fromPayload(array $payload): ProspectNotInterestedEvent
    {
        return new self($payload['prospect_id']);
    }
}
