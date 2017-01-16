<?php

namespace Mannion007\BestInvestments\Domain\ProjectManagement;

class ProjectStarted extends DomainEvent implements DomainEventInterface
{
    const EVENT_NAME = 'project_started';

    private $reference;

    public function __construct(
        ProjectReference $reference
    ) {
        parent::__construct();
        $this->reference = $reference;
    }

    public function getPayload(): array
    {
        return ['reference' => $this->reference];
    }

    public static function fromPayload(array $payload) : DomainEvent
    {
        return new self(ProjectReference::fromExisting($payload['reference']));
    }
}
