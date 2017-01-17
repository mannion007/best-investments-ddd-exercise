<?php

namespace Mannion007\BestInvestments\Domain\ProjectManagement;

class Consultation
{
    private $consultationId;
    private $projectReference;
    private $specialistId;
    private $time;
    private $status;
    private $duration;

    public function __construct(
        ConsultationId $consultationId,
        ProjectReference $projectReference,
        SpecialistId $specialistId,
        \DateTime $time
    ) {
        $this->consultationId = $consultationId;
        $this->projectReference = $projectReference;
        $this->specialistId = $specialistId;
        $this->time = $time;
        $this->status = ConsultationStatus::open();
        $this->duration = new TimeIncrement(0);
    }

    public function report(int $durationMinutes)
    {
        if ($this->status->isNot(ConsultationStatus::OPEN)) {
            throw new \DomainException('Cannot report on a consultation that is not open');
        }
        $this->duration = $this->duration->add(new TimeIncrement($durationMinutes));
        $this->status = ConsultationStatus::confirmed();
    }

    public function discard()
    {
        if ($this->status->isNot(ConsultationStatus::OPEN)) {
            throw new \DomainException('Cannot discard a report on a consultation that is not open');
        }
        $this->status = ConsultationStatus::discarded();
    }

    public function is($status): bool
    {
        return $this->status->is($status);
    }
}
