<?php

namespace Mannion007\BestInvestments\Domain\ProjectManagement;

use Ramsey\Uuid\Uuid;

class SpecialistId
{
    private $id;

    public function __construct(string $id = null)
    {
        $this->id = is_null($id) ? Uuid::uuid4()->toString() : $id;
    }

    public static function fromExisting(string $id) : SpecialistId
    {
        return new self($id);
    }

    public function __toString()
    {
        return (string)$this->id;
    }
}
