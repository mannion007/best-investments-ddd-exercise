<?php

namespace Mannion007\BestInvestments\Domain\Invoicing;

class ConsultationId
{
    private $consultationId;

    private function __construct(int $consultationId)
    {
        $this->consultationId = $consultationId;
    }

    public static function fromExisting(int $consultationId): ConsultationId
    {
        return new self($consultationId);
    }

    public function __toString()
    {
        return (string)$this->consultationId;
    }
}
