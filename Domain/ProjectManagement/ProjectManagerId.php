<?php

namespace Mannion007\BestInvestments\Domain\ProjectManagement;

class ProjectManagerId
{
    private $id;

    private function __construct(string $id)
    {
        $this->id = $id;
    }

    public function fromExisting(string $id) : ProjectManagerId
    {
        return new self($id);
    }

    public function __toString()
    {
        return (string)$this->id;
    }
}
