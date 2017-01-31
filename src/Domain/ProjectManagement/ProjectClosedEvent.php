<?php

namespace Mannion007\BestInvestments\Domain\ProjectManagement;

use Mannion007\BestInvestments\Event\EventInterface;

class ProjectClosedEvent implements EventInterface
{
    const EVENT_NAME = 'project_closed';

    private $reference;
    private $occurredAt;

    public function __construct($reference, \DateTimeInterface $occurredAt = null)
    {
        $this->reference = $reference;
        $this->occurredAt = is_null($occurredAt) ? new \DateTime() : $occurredAt;
    }

    public function getReference(): string
    {
        return $this->reference;
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
        return ['reference' => $this->reference];
    }

    public static function fromPayload(array $payload): ProjectClosedEvent
    {
        return new self($payload['reference']);
    }
}
