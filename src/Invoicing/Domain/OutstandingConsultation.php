<?php

namespace Mannion007\BestInvestments\Invoicing\Domain;

class OutstandingConsultation
{
    /** @var ConsultationId */
    private $consultationId;

    /** @var ClientId */
    private $clientId;

    /** @var TimeIncrement */
    private $duration;

    public function __construct(ConsultationId $consultationId, ClientId $clientId, int $durationMinutes)
    {
        $this->consultationId = $consultationId;
        $this->clientId = $clientId;
        $this->duration = new TimeIncrement($durationMinutes);
    }

    public function getConsultationId(): ConsultationId
    {
        return $this->consultationId;
    }

    public function getClientId(): ClientId
    {
        return $this->clientId;
    }

    public function getDuration(): TimeIncrement
    {
        return $this->duration;
    }
}
