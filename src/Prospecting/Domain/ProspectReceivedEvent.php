<?php

namespace Mannion007\BestInvestments\Prospecting\Domain;

use Mannion007\BestInvestments\Event\EventInterface;

class ProspectReceivedEvent implements EventInterface
{
    const EVENT_NAME = 'prospect_received';

    private $prospectId;
    private $name;
    private $notes;
    private $occurredAt;

    public function __construct($prospectId, $name, $notes, \DateTimeInterface $occurredAt = null)
    {
        $this->prospectId = $prospectId;
        $this->name = $name;
        $this->notes = $notes;
        $this->occurredAt = is_null($occurredAt) ? new \DateTime() : $occurredAt;
    }

    public function getProspectId(): string
    {
        return $this->prospectId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getNotes(): string
    {
        return $this->notes;
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
            'prospect_id' => $this->prospectId,
            'name' => $this->name,
            'notes' => $this->notes
        ];
    }

    public static function fromPayload(array $payload): ProspectReceivedEvent
    {
        return new self($payload['prospect_id'], $payload['name'], $payload['notes']);
    }
}
