<?php

namespace Mannion007\BestInvestments\ProjectManagement\Domain;

use Mannion007\Interfaces\Event\EventInterface;

class ConsultationReportedEvent implements EventInterface
{
    const EVENT_NAME = 'consultation_reported';

    private $projectReference;
    private $consultationId;
    private $duration;
    private $occurredAt;

    public function __construct(
        string $projectReference,
        string $consultationId,
        int $duration,
        \DateTimeInterface $occurredAt = null
    ) {
        $this->projectReference = $projectReference;
        $this->consultationId = $consultationId;
        $this->duration = $duration;
        $this->occurredAt = is_null($occurredAt) ? new \DateTime() : $occurredAt;
    }

    public function getProjectReference(): string
    {
        return $this->projectReference;
    }

    public function getConsultationId(): string
    {
        return $this->consultationId;
    }

    public function getDuration(): int
    {
        return $this->duration;
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
            'project_reference' => $this->projectReference,
            'consultation_id' => $this->consultationId,
            'duration' => $this->duration
        ];
    }

    public static function fromPayload(array $payload): ConsultationReportedEvent
    {
        return new self($payload['project_reference'], $payload['consultation_id'], $payload['duration']);
    }
}
