<?php

namespace Mannion007\BestInvestments\Domain\Prospecting;

use Mannion007\BestInvestments\Event\EventInterface;

class ProspectReceived implements EventInterface
{
    const EVENT_NAME = 'prospect_received';

    private $prospectId;
    private $name;
    private $notes;
    private $occurredAt;

    public function __construct(
        ProspectId $prospectId,
        string $name,
        string $notes,
        \DateTime $occurredAt = null
    ) {
        $this->prospectId = $prospectId;
        $this->name = $name;
        $this->notes = $notes;
        $this->occurredAt = is_null($occurredAt) ? new \DateTime() : $occurredAt;
    }

    public function getProspectId(): ProspectId
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

    public function getEventName() : string
    {
        return self::EVENT_NAME;
    }

    public function getOccurredAt() : \DateTime
    {
        return $this->occurredAt;
    }

    public function getPayload(): array
    {
        return
        [
            'prospect_id' => (string)$this->prospectId,
            'name' => $this->name,
            'notes' => $this->notes
        ];
    }

    public static function fromPayload(array $payload) : ProspectReceived
    {
        return new self(
            ProspectId::fromExisting($payload['prospect_id']),
            $payload['name'],
            $payload['notes']
        );
    }
}
