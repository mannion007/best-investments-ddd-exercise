<?php

namespace Mannion007\BestInvestments\Domain\Prospecting;

class ProspectId
{
    private $id;

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
