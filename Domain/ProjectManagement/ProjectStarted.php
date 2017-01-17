<?php

namespace Mannion007\BestInvestments\Domain\ProjectManagement;

class ProjectStarted implements DomainEventInterface
{
    const EVENT_NAME = 'project_started';

    private $reference;
    private $projectManagerId;
    private $occurredAt;

    public function __construct(
        ProjectReference $reference,
        ProjectManagerId $projectManagerId,
        \DateTime $occurredAt = null
    ) {
        $this->reference = $reference;
        $this->projectManagerId = $projectManagerId;
        $this->occurredAt = is_null($occurredAt) ? new \DateTime() : $occurredAt;
    }

    public function getReference(): ProjectReference
    {
        return $this->reference;
    }

    public function getProjectManagerId(): ProjectManagerId
    {
        return $this->projectManagerId;
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
        return ['reference' => (string)$this->reference];
    }

    public static function fromPayload(array $payload) : ProjectStarted
    {
        return new self(
            ProjectReference::fromExisting($payload['reference']),
            ProjectManagerId::fromExisting($payload['project_manager_id'])
        );
    }
}
