<?php

namespace Mannion007\BestInvestments\Domain\ProjectManagement;

use Mannion007\BestInvestments\Event\EventInterface;

class SpecialistApprovedEvent implements EventInterface
{
    const EVENT_NAME = 'specialist_approved';

    private $reference;
    private $specialistId;
    private $occurredAt;

    public function __construct($reference, $specialistId, \DateTimeInterface $occurredAt = null)
    {
        $this->reference = $reference;
        $this->specialistId = $specialistId;
        $this->occurredAt = is_null($occurredAt) ? new \DateTime() : $occurredAt;
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    public function getSpecialistId(): string
    {
        return $this->specialistId;
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
            'specialist_id' => $this->specialistId
        ];
    }

    public static function fromPayload(array $payload): SpecialistApprovedEvent
    {
        return new self($payload['reference'], $payload['specialist_id']);
    }
}
