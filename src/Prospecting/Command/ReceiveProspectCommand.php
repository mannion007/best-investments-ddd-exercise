<?php

namespace Mannion007\BestInvestments\Prospecting\Command;

class ReceiveProspectCommand
{
    private $prospectId;
    private $name;
    private $notes;

    public function __construct(string $prospectId, string $name, string $notes)
    {
        $this->prospectId = $prospectId;
        $this->name = $name;
        $this->notes = $notes;
    }

    public function getProspectId(): string
    {
        return $this->prospectId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getNotes(): string
    {
        return $this->notes;
    }

    public static function fromPayload(array $payload): ReceiveProspectCommand
    {
        return new self($payload['prospect_id'], $payload['name'], $payload['notes']);
    }
}
