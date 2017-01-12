<?php

namespace Mannion007\BestInvestments\Domain\Prospecting;

class ProspectId
{
    private $id;

    private function __construct(string $id)
    {
        $this->id = $id;
    }

    public static function fromExisting(string $id) : ProspectId
    {
        return new self($id);
    }
}