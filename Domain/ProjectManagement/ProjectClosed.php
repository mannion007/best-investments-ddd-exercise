<?php

namespace Mannion007\BestInvestments\Domain\ProjectManagement;

use Mannion007\BestInvestments\Event\EventInterface;

class ProjectClosed implements EventInterface
{
    const EVENT_NAME = 'project_closed';

    private $reference;
    private $occurredAt;

    public function __construct(ProjectReference $reference, \DateTime $occurredAt = null)
    {
        $this->reference = $reference;
        $this->occurredAt = is_null($occurredAt) ? new \DateTime() : $occurredAt;
    }

    public function getReference(): ProjectReference
    {
        return $this->reference;
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

    public static function fromPayload(array $payload) : ProjectClosed
    {
        return new self(ProjectReference::fromExisting($payload['reference']));
    }
}
