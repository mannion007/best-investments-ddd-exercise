<?php

namespace Mannion007\BestInvestments\ProjectManagement\Domain;

use Mannion007\Interfaces\Event\EventInterface;

class ConsultationDiscardedEvent implements EventInterface
{
    const EVENT_NAME = 'consultation_discarded';

    private $projectReference;
    private $consultationId;
    private $occurredAt;

    public function __construct(
        string $projectReference,
        string $consultationId,
        \DateTimeInterface $occurredAt = null
    ) {
        $this->projectReference = $projectReference;
        $this->consultationId = $consultationId;
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
            'consultation_id' => $this->consultationId
        ];
    }

    public static function fromPayload(array $payload): ConsultationDiscardedEvent
    {
        return new self($payload['project_reference'], $payload['consultation_id']);
    }
}
