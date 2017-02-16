<?php

namespace Mannion007\BestInvestments\Invoicing\Command;

class ScheduleConsultationCommand
{
    private $consultationId;
    private $clientId;
    private $duration;

    public function __construct(string $consultationId, string $clientId, string $duration)
    {
        $this->consultationId = $consultationId;
        $this->clientId = $clientId;
        $this->duration = $duration;
    }

    public function getConsultationId(): string
    {
        return $this->consultationId;
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function getDuration(): string
    {
        return $this->duration;
    }

    public static function fromPayload(array $payload): ScheduleConsultationCommand
    {
        return new self($payload['consultation_id'], $payload['client_id'], $payload['duration']);
    }
}
