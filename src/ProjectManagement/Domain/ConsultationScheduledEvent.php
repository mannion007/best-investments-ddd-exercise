<?php

namespace Mannion007\BestInvestments\ProjectManagement\Domain;

use Mannion007\Interfaces\Event\EventInterface;

class ConsultationScheduledEvent implements EventInterface
{
    const EVENT_NAME = 'consultation_scheduled';

    private $reference;
    private $specialistId;
    private $time;
    private $occurredAt;

    public function __construct(
        string $reference,
        string$specialistId,
        string $time,
        \DateTimeInterface $occurredAt = null
    ) {
        $this->reference = $reference;
        $this->specialistId = $specialistId;
        $this->time = $time;
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

    public function getTime(): string
    {
        return $this->time;
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
        return ['reference' => $this->reference, 'specialist_id' => $this->specialistId, 'time' => $this->time];
    }

    public static function fromPayload(array $payload): ConsultationScheduledEvent
    {
        return new self($payload['reference'], $payload['specialist_id'], $payload['time']);
    }
}
