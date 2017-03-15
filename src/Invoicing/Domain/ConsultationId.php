<?php

namespace Mannion007\BestInvestments\Invoicing\Domain;

class ConsultationId
{
    private $consultationId;

    public function __construct(string $consultationId)
    {
        $this->consultationId = $consultationId;
    }

    public static function fromExisting(string $existing): ConsultationId
    {
        return new self($existing);
    }

    public function is(ConsultationId $other): bool
    {
        return (string)$other === (string)$this;
    }

    public function __toString()
    {
        return (string)$this->consultationId;
    }
}
