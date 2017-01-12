<?php

namespace Mannion007\BestInvestments\Domain\Invoicing;

class Consultation
{
    private $consultationId;
    private $clientId;
    private $duration;

    public function __construct(ConsultationId $consultationId, ClientId $clientId, TimeIncrement $duration)
    {
        $this->consultationId = $consultationId;
        $this->clientId = $clientId;
        $this->duration = $duration;
    }

    public function getClientId() : ClientId
    {
        return $this->clientId;
    }

    public function getDuration() : TimeIncrement
    {
        return $this->duration;
    }
}
