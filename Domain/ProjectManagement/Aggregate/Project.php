<?php

class Project
{
    private $reference;
    private $clientId;
    private $projectManagerId;

    private $name;
    private $status;
    private $deadline;

    /** @var SpecialistCollection  */
    private $specialists;

    /** @var Consultation[]  */
    private $consultations = [];

    private function __construct(ClientId $clientId, string $name, DateTime $deadline)
    {
        $this->reference = new ProjectReference();
        $this->clientId = $clientId;
        $this->name = $name;
        $this->deadline = $deadline;
        $this->specialists = new SpecialistCollection();

        $this->status = ProjectStatus::Draft();
        /** Raise a 'project_set_up' event */
    }

    public static function setUp(ClientId $clientId, string $name, DateTime $deadline) : Project
    {
        return new self($clientId, $name, $deadline);
    }

    public function start(ProjectManagerId $projectManagerId)
    {
        if ($this->status->is(ProjectStatus::ACTIVE)) {
            throw new Exception('The project has already started, it cannot be started again');
        }
        $this->projectManagerId = $projectManagerId;
        $this->status = ProjectStatus::active();
        /** Raise a 'project_started' event */
    }

    public function close()
    {
        /** @var Consultation $consultation */
        foreach ($this->consultations as $consultation) {
            if ($consultation->is(ConsultationStatus::OPEN)) {
                throw new Exception(
                    'Cannot close project until all open Consultations have been either Confirmed or Discarded'
                );
            }
        }
        $this->status = ProjectStatus::closed();
        /** Raise a 'project_closed' event */
    }

    public function addSpecialist(SpecialistId $specialistId)
    {
        if ($this->specialists->includes((string)$specialistId)) {
            throw new Exception('Cannot add a specialist more than once');
        }
        if ($this->status->isNot(ProjectStatus::ACTIVE)) {
            throw new Exception('A specialist can only be added to a project after it has started');
        }
        $this->specialists[(string)$specialistId] = SpecialistRecommendation::unvetted();
    }

    public function approveSpecialist(SpecialistId $specialistId)
    {
        if ($this->specialists[(string)$specialistId]->isNot(SpecialistRecommendation::UNVETTED)) {
            throw new Exception('Potential specialist is not unvetted');
        }
        $this->specialists[(string)$specialistId] = SpecialistRecommendation::approved();
    }

    public function discardSpecialist(SpecialistId $specialistId)
    {
        if ($this->specialists[(string)$specialistId]->isNot(SpecialistRecommendation::UNVETTED)) {
            throw new Exception('Potential specialist is not unvetted');
        }
        $this->specialists[(string)$specialistId] = SpecialistRecommendation::discarded();
    }

    public function scheduleConsultation(SpecialistId $specialistId, DateTime $time)
    {
        if ($this->isNot(ProjectStatus::ACTIVE)) {
            throw new Exception('Can not schedule a Consultation for a Project that is not active');
        }
        if ($this->specialists[(string)$specialistId]->isNot(SpecialistRecommendation::APROVED)) {
            throw new Exception('A consultation can only be scheduled with an approved Specialist');
        }
        $this->consultations = new Consultation($this->nextConsultationId(), $this->reference, $specialistId, $time);
    }

    public function putOnHold()
    {
        /** Need to enforce this, or if not on hold just do nothing? */
        if ($this->status->isNot(ProjectStatus::ACTIVE)) {
            throw new Exception('Can only put an active project on hold');
        }
        $this->status = ProjectStatus::ON_HOLD;
    }

    public function reportConsultation(ConsultationId $consultationId, int $durationMinutes)
    {
        /** While there is a rule that you can't do this if the project is closed, that is already guarded in that
         *  a Project can only be put into the Closed state when all Consultations are Closed or Discarded */
        $this->consultations[(string)$consultationId]->report($durationMinutes);
    }

    public function discardConsultation(ConsultationId $consultationId)
    {
        /** While there is a rule that you can't do this if the project is closed, that is already guarded in that
         *  a Project can only be put into the Closed state when all Consultations are Closed or Discarded */
        $this->consultations[(string)$consultationId]->discard();
    }

    public function getReference() : ProjectReference
    {
        return $this->reference;
    }

    private function nextConsultationId() : ConsultationId
    {
        return new ConsultationId(count($this->consultations));
    }

    public function is($status) : bool
    {
        return $this->status->is($status);
    }

    public function isNot($status) : bool
    {
        return $this->status->isNot($status);
    }
}
