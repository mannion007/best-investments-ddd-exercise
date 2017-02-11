<?php

namespace Mannion007\BestInvestments\Prospecting\Application\Command;

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

    public static function fromPayload(array $payload)
    {
        return new self($payload['prospect-id']);
    }
}
