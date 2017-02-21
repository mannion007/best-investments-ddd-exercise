<?php

namespace Mannion007\BestInvestments\ProjectManagement\Domain;

use Mannion007\Interfaces\Event\EventInterface;

class ProjectClosedEvent implements EventInterface
{
    const EVENT_NAME = 'project_closed';

    private $reference;
    private $occurredAt;

    public function __construct(string $reference, \DateTimeInterface $occurredAt = null)
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
