<?php

class Project
{
    private $reference;
    private $clientId;
    private $projectManagerId;

    private $name;
    private $status;
    private $endDate;

    /** @var SpecialistCollection  */
    private $specialists;

    /** @var Consultation[]  */
    private $consultations = [];

    private function __construct(ClientId $clientId, string $name, \DateTime $endDate)
    {
        $this->reference = ProjectReference::create();
        $this->clientId = $clientId;
        $this->name = $name;
        $this->endDate = $endDate;
        $this->specialists = new SpecialistCollection();

        $this->status = ProjectStatus::Draft();
        /** Raise a 'project_set_up' event */
    }

    public static function setUp(ClientId $clientId, string $name, \DateTime $endDate)
    {
        return new self($clientId, $name, $endDate);
    }

    public function start(ProjectManagerId $projectManagerId)
    {
        if ($this->status->is(ProjectStatus::ACTIVE)) {
            throw new \Exception('The project has already started, it cannot be started again');
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
                    'Cannot close project until all open consultations have been either discarded or confirmed'
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
        if ($this->specialists[(string)$specialistId]->isNot(SpecialistRecommendation::APROVED)) {
            throw new Exception('A consultation can only be scheduled with an approved Specialist');
        }
        $this->consultations = new Consultation($this, $specialistId, $time);
    }

    public function getReference()
    {
        return $this->reference;
    }

    public function is($status)
    {
        return $this->status->is($status);
    }

    public function isNot($status)
    {
        return $this->status->isNot($status);
    }
}
