<?php

namespace Mannion007\BestInvestments\Prospecting\Command;

class ChaseUpProspectCommand
{
    private $prospectId;

    public function __construct(string $prospectId)
    {
        $this->prospectId = $prospectId;
    }

    public function getProspectId(): string
    {
        return $this->prospectId;
    }

    public static function fromPayload(array $payload): ChaseUpProspectCommand
    {
        return new self($payload['prospect_id']);
    }
}
