<?php

namespace Mannion007\BestInvestments\ProjectManagement\Domain;

use Mannion007\BestInvestments\Event\EventInterface;

class ProjectDraftedEvent implements EventInterface
{
    const EVENT_NAME = 'project_drafted';

    private $reference;
    private $clientId;
    private $name;
    private $deadline;
    private $occurredAt;

    public function __construct($reference, $clientId, $name, $deadline, \DateTimeInterface $occurredAt = null)
    {
        $this->reference = $reference;
        $this->clientId = $clientId;
        $this->name = $name;
        $this->deadline = $deadline;
        $this->occurredAt = is_null($occurredAt) ? new \DateTime() : $occurredAt;
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDeadline(): string
    {
        return $this->deadline;
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
            'reference' => $this->reference,
            'client_id' => $this->reference,
            'name' => $this->name,
            'deadline' => $this->deadline
        ];
    }

    public static function fromPayload(array $payload): ProjectDraftedEvent
    {
        return new self($payload['reference'], $payload['client_id'], $payload['name'], $payload['deadline']);
    }
}
