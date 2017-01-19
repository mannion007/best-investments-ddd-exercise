<?php

namespace Mannion007\BestInvestments\Domain\Invoicing;

class Consultation
{
    private $consultationId;
    private $clientId;
    private $duration;
    private $projectStatus;

    private function __construct(
        ConsultationId $consultationId,
        ClientId $clientId,
        TimeIncrement $duration,
        ProjectStatus $projectStatus
    ) {
        $this->consultationId = $consultationId;
        $this->clientId = $clientId;
        $this->duration = $duration;
        $this->projectStatus = $projectStatus;
    }

    public static function schedule(ConsultationId $consultationId, ClientId $clientId, TimeIncrement $duration)
    {
        return new self($consultationId, $clientId, $duration, ProjectStatus::active());
    }

    public function endProject()
    {
        if ($this->projectStatus->isNot(ProjectStatus::ACTIVE)) {
            throw new \DomainException('Cannot end a Project that is not active');
        }
        $this->projectStatus = ProjectStatus::ended();
    }

    public function getConsultationId()
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

    public function isNotBillable()
    {
        return !$this->isBillable();
    }

    public function isBillable()
    {
        return $this->projectStatus->is(ProjectStatus::ENDED);
    }
}
