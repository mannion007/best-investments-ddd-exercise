<?php

namespace Mannion007\BestInvestments\Invoicing\Command;

class EndProjectCommand
{
    private $consultationId;

    public function __construct(string $consultationId)
    {
        $this->consultationId = $consultationId;
    }

    public function getConsultationId(): string
    {
        return $this->consultationId;
    }

    public static function fromPayload(array $payload): EndProjectCommand
    {
        return new self($payload['consultation_id']);
    }
}
