<?php

class Project
{
    private $reference;
    private $clientId;
    private $projectManagerId;

    private $name;
    private $status;
    private $endDate;

    /** @var SpecialistId[] */
    private $specialists = [];

    /** @var Consultation[]  */
    private $consultations = [];

    private function __construct(ClientId $clientId, string $name, \DateTime $endDate)
    {
        $this->reference = ProjectReference::create();
        $this->status = ProjectStatus::Draft();

        $this->clientId = $clientId;
        $this->name = $name;
        $this->endDate = $endDate;
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
        if (array_key_exists((string)$specialistId, $this->specialists)) {
            throw new Exception('Cannot add a specialist more than once');
        }
        if (!$this->status->is(ProjectStatus::ACTIVE)) {
            throw new Exception('A specialist can only be added to a project after it has started');
        }
        $this->specialists[(string)$specialistId] = SpecialistRecommendation::unvetted();
    }

    public function approveSpecialist(SpecialistId $specialistId)
    {
        if (!$this->specialists[(string)$specialistId]->is(SpecialistRecommendation::UNVETTED)) {
            throw new Exception('Potential specialist is not unvetted');
        }
        $this->specialists[(string)$specialistId] = SpecialistRecommendation::approved();
    }

    public function discardSpecialist(SpecialistId $specialistId)
    {
        if (!$this->specialists[(string)$specialistId]->is(SpecialistRecommendation::UNVETTED)) {
            throw new Exception('Potential specialist is not unvetted');
        }
        $this->specialists[(string)$specialistId] = SpecialistRecommendation::discarded();
    }

    /**
     * Belongs here because there is a rule that a consultation can only be scheduled for an active project
     */
    public function scheduleConsultation(SpecialistId $specialistId, DateTime $time)
    {
        if (!$this->status->is(ProjectStatus::ACTIVE)) {
            throw new \Exception('A specialist can only be added to a project after it has started');
        }
        if (!$this->specialists[(string)$specialistId]->status->is(SpecialistRecommendation::APROVED)) {
            throw new Exception('A consultation can only be scheduled with an approved Specialist');
        }
        $this->consultations = new Consultation($specialistId, $time);
    }
}
