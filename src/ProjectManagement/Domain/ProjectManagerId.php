<?php

namespace Mannion007\BestInvestments\ProjectManagement\Domain;

class ProjectManagerId
{
    private $id;

    private function __construct(string $id)
    {
        $this->id = $id;
    }

    public static function fromExisting(string $id): ProjectManagerId
    {
        return new self($id);
    }

    public function __toString()
    {
        return (string)$this->id;
    }
}
