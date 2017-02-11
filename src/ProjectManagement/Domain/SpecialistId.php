<?php

namespace Mannion007\BestInvestments\ProjectManagement\Domain;

use Ramsey\Uuid\Uuid;

class SpecialistId
{
    private $specialistId;

    public function __construct(string $specialistId = null)
    {
        $this->specialistId = is_null($specialistId) ? Uuid::uuid4()->toString() : $specialistId;
    }

    public static function fromExisting(string $specialistId): SpecialistId
    {
        return new self($specialistId);
    }

    public function __toString()
    {
        return (string)$this->specialistId;
    }
}
