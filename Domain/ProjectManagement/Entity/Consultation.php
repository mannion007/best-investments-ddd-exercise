<?php

class Consultation
{
    private $specialistId;
    private $time;
    private $status;

    private function __construct(SpecialistId $specialistId, DateTime $time)
    {
        $this->specialistId = $specialistId;
        $this->time = $time;
    }

    public statuic function schedule(SpecialistId $specialistId, DateTime $time)
    {
        $this->status = ConsultationStatus::OPEN;
    }
}
