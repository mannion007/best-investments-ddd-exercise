<?php

namespace Mannion007\BestInvestments\Domain\ProjectManagement;

class SpecialistApprovedEvent
{
    const EVENT_NAME = 'project_closed';

    private $reference;
    private $specialistId;

    public function __construct(ProjectReference $reference, SpecialistId $specialistId)
    {
        $this->reference = $reference;
        $this->specialistId = $specialistId;
    }

    public static function fromPayload(array $payload)
    {
        return new self(
            $payload->reference,
            new SpecialistId($payload->specialistId)
        );
    }
}
