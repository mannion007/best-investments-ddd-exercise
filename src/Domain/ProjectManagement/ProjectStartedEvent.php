<?php

namespace Mannion007\BestInvestments\Domain\ProjectManagement;

use Mannion007\BestInvestments\Event\EventInterface;

class ProjectStartedEvent implements EventInterface
{
    const EVENT_NAME = 'project_started';

    private $reference;
    private $projectManagerId;
    private $occurredAt;

    public function __construct($reference, $projectManagerId, \DateTime $occurredAt = null)
    {
        $this->reference = $reference;
        $this->projectManagerId = $projectManagerId;
        $this->occurredAt = is_null($occurredAt) ? new \DateTime() : $occurredAt;
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    public function getProjectManagerId(): string
    {
        return $this->projectManagerId;
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
            'project_manager_id' => $this->projectManagerId
        ];
    }

    public static function fromPayload(array $payload): ProjectStartedEvent
    {
        return new self($payload['reference'], $payload['project_manager_id']);
    }
}
