<?php

namespace Mannion007\BestInvestments\Domain\ProjectManagement;

class ProjectClosedEvent
{
    const EVENT_NAME = 'project_closed';

    private $reference;
    private $clientId;
    private $consultations;

    public function __construct(ProjectReference $reference, ClientId $clientId, array $consultations)
    {
        $this->reference = $reference;
        $this->clientId = $clientId;
        $this->consultations = $consultations;
    }

    public static function fromPayload(array $payload)
    {
        return new self(
            ProjectReference::fromExisting($payload->reference),
            ClientId::fromExisting($payload->clientId),
            $payload->consultations
        );
    }
}
