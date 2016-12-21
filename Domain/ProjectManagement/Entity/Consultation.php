<?php

class Consultation
{
    private $consultationId;
    private $specialistId;
    private $time;
    private $status;
    private $durationMinutes;

    public function __construct(SpecialistId $specialistId, DateTime $time)
    {
        $this->consultationId = new ConsultationId();
        $this->specialistId = $specialistId;
        $this->time = $time;
        $this->status = ConsultationStatus::open();
    }

    public function report(int $durationMinutes)
    {
        if (!$this->status->is(ConsultationStatus::OPEN)) {
            throw new Exception('Cannot report on a consultation that is not open');
        }
        $this->durationMinutes = $durationMinutes;
        $this->status = ConsultationStatus::confirmed();
    }

    /** @todo implement discard */
    public function discard()
    {
        if (!$this->status->is(ConsultationStatus::OPEN)) {
            throw new Exception('Cannot discard a report on a consultation that is not open');
        }
        $this->status = ConsultationStatus::discarded();
    }

    public function is($value)
    {
        return $this->status->is($value);
    }
}
