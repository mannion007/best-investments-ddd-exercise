<?php

namespace Mannion007\BestInvestments\Prospecting\Command;

class RegisterProspectCommand
{
    private $prospectId;
    private $hourlyRate;

    public function __construct(string $prospectId, string $hourlyRate)
    {
        $this->prospectId = $prospectId;
        $this->hourlyRate = $hourlyRate;
    }

    public function getProspectId(): string
    {
        return $this->prospectId;
    }

    public function getHourlyRate(): string
    {
        return $this->hourlyRate;
    }

    public static function fromPayload(array $payload): RegisterProspectCommand
    {
        return new self($payload['prospect_id'], $payload['hourly_rate']);
    }
}
