<?php

namespace Mannion007\BestInvestments\Domain\ProjectManagement;

class ProjectStartedEvent
{
    const EVENT_NAME = 'project_started';

    private $reference;
    private $projectManagerId;

    public function __construct(
        ProjectReference $reference,
        ProjectManagerId $projectManagerId
    ) {
        $this->reference = $reference;
        $this->projectManagerId = $projectManagerId;
    }

    public static function fromPayload(array $payload)
    {
        return new self(
            ProjectReference::fromExisting($payload->reference),
            ProjectManagerId::fromExisting($payload->project_manager_id)
        );
    }
}
