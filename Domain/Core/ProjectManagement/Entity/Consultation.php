<?php

class Consultation
{
    private $consultationId;
    private $projectReference;
    private $specialistId;
    private $time;
    private $status;
    private $durationMinutes;

    public function __construct(
        ConsultationId $consultationId,
        Project $project,
        SpecialistId $specialistId,
        DateTime $time
    ) {
        if ($project->isNot(ProjectStatus::ACTIVE)) {
            throw new Exception('Can only create a new Consultation for an active project.');
        }
        $this->consultationId = $consultationId;
        $this->projectReference = $project->getReference();
        $this->specialistId = $specialistId;
        $this->time = $time;
        $this->status = ConsultationStatus::open();
    }

    public function report(int $durationMinutes)
    {
        if ($this->status->isNot(ConsultationStatus::OPEN)) {
            throw new Exception('Cannot report on a consultation that is not open');
        }
        $this->durationMinutes = $durationMinutes;
        $this->status = ConsultationStatus::confirmed();
    }

    public function discard()
    {
        if ($this->status->isNot(ConsultationStatus::OPEN)) {
            throw new Exception('Cannot discard a report on a consultation that is not open');
        }
        $this->status = ConsultationStatus::discarded();
    }

    public function is($status) : bool
    {
        return $this->status->is($status);
    }
}
