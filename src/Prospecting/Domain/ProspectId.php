<?php

namespace Mannion007\BestInvestments\Prospecting\Domain;

class ProspectId
{
    private $prospectId;

    private function __construct(string $prospectId)
    {
        $this->prospectId = $prospectId;
    }

    public static function fromExisting(string $prospectId): ProspectId
    {
        return new self($prospectId);
    }

    public function __toString()
    {
        return (string)$this->prospectId;
    }
}
