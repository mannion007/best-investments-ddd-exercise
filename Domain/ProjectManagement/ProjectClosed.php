<?php

namespace Mannion007\BestInvestments\Domain\ProjectManagement;

class ProjectClosed extends DomainEvent
{
    const EVENT_NAME = 'project_closed';

    private $reference;

    public function __construct(ProjectReference $reference)
    {
        parent::__construct(self::EVENT_NAME);
        $this->reference = $reference;
    }

    public function getPayload(): array
    {
        return [
          'reference' => $this->reference
        ];
    }

    public static function fromPayload(array $payload) : DomainEvent
    {
        return new self(
            ProjectReference::fromExisting($payload['reference'])
        );
    }
}
