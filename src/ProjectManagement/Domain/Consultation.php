<?php

namespace Mannion007\BestInvestments\ProjectManagement\Domain;

use Mannion007\BestInvestments\EventPublisher\EventPublisher;

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
        \DateTimeInterface $time
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
            throw new \Exception('Cannot report on a consultation that is not open');
        }
        $this->duration = $this->duration->add(new TimeIncrement($durationMinutes));
        $this->status = ConsultationStatus::confirmed();
        EventPublisher::publish(
            new ConsultationReportedEvent(
                (string)$this->projectReference,
                (string)$this->consultationId,
                $this->duration->inMinutes()
            )
        );
    }

    public function discard()
    {
        if ($this->status->isNot(ConsultationStatus::OPEN)) {
            throw new \Exception('Cannot discard a report on a consultation that is not open');
        }
        $this->status = ConsultationStatus::discarded();
        EventPublisher::publish(
            new ConsultationDiscardedEvent(
                (string)$this->projectReference,
                (string)$this->consultationId
            )
        );
    }

    public function isNot($status): bool
    {
        return !$this->is($status);
    }

    public function is($status): bool
    {
        return $this->status->is($status);
    }

    public function getConsultationId(): ConsultationId
    {
        return $this->consultationId;
    }
}
