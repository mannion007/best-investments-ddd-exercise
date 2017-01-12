<?php

namespace Mannion007\BestInvestments\Domain\ProjectManagement;

use Ramsey\Uuid\Uuid;

class SpecialistId
{
    private $id;

    public function __construct()
    {
        $this->id = Uuid::uuid4()->toString();
    }

    public function __toString()
    {
        return (string)$this->id;
    }
}
