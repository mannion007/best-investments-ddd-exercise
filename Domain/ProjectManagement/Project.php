<?php

namespace Mannion007\BestInvestments\ProjectManagement;

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

    private function __construct(ClientId $clientId, string $name, \DateTime $deadline)
    {
        $this->reference = new ProjectReference();
        $this->clientId = $clientId;
        $this->name = $name;
        $this->deadline = $deadline;
        $this->specialists = new SpecialistCollection();

        $this->status = ProjectStatus::draft();
        /** Raise a 'project_drafted' event */
    }

    public static function setUp(ClientId $clientId, string $name, \DateTime $deadline) : Project
    {
        return new self($clientId, $name, $deadline);
    }

    public function start(ProjectManagerId $projectManagerId)
    {
        if ($this->status->isNot(ProjectStatus::DRAFT)) {
            throw new \DomainException('Cannot Start a Project that is not in Draft state');
        }
        $this->projectManagerId = $projectManagerId;
        $this->status = ProjectStatus::active();
        /** Raise a 'project_started' event */
    }

    public function close()
    {
        /** This cannot be event driven. Client may want to arrange more consultations at any time */
        /** @var Consultation $consultation */
        foreach ($this->consultations as $consultation) {
            if ($consultation->is(ConsultationStatus::OPEN)) {
                throw new \DomainException(
                    'Cannot close project until all open Consultations have been either Confirmed or Discarded'
                );
            }
        }
        $this->status = ProjectStatus::closed();
        /** Raise a 'project_closed' event */
    }

    public function addSpecialist(SpecialistId $specialistId)
    {
        if ($this->status->isNot(ProjectStatus::ACTIVE)) {
            throw new \DomainException('A specialist can only be added to a project after it has started');
        }
        if ($this->specialists->includes((string)$specialistId)) {
            throw new \DomainException('Cannot add a specialist more than once');
        }
        $this->specialists[(string)$specialistId] = SpecialistRecommendation::unvetted();
    }

    public function approveSpecialist(SpecialistId $specialistId)
    {
        if ($this->specialists[(string)$specialistId]->isNot(SpecialistRecommendation::UNVETTED)) {
            throw new \DomainException('Potential specialist is not unvetted');
        }
        $this->specialists[(string)$specialistId] = SpecialistRecommendation::approved();
        /** Raise a 'specialist_approved' event */
    }

    public function discardSpecialist(SpecialistId $specialistId)
    {
        if ($this->specialists[(string)$specialistId]->isNot(SpecialistRecommendation::UNVETTED)) {
            throw new \DomainException('Potential specialist is not unvetted');
        }
        $this->specialists[(string)$specialistId] = SpecialistRecommendation::discarded();
        /** Raise a 'specialist_discarded' event */
    }

    public function scheduleConsultation(SpecialistId $specialistId, DateTime $time)
    {
        if ($this->isNot(ProjectStatus::ACTIVE)) {
            throw new \DomainException('Can not schedule a Consultation for a Project that is not active');
        }
        if ($this->specialists[(string)$specialistId]->isNot(SpecialistRecommendation::APROVED)) {
            throw new \DomainException('A consultation can only be scheduled with an approved Specialist');
        }
        $this->consultations = new Consultation($this->nextConsultationId(), $this->reference, $specialistId, $time);
    }
    
    public function putOnHold()
    {
        /** Need to enforce this, or if not on hold just do nothing? */
        if ($this->status->isNot(ProjectStatus::ACTIVE)) {
            throw new \DomainException('Can only put an active project on hold');
        }
        $this->status = ProjectStatus::onHold();
    }

    public function reactivate()
    {
        if ($this->status->isNot(ProjectStatus::ON_HOLD)) {
            throw new Exception('Cannot reactivate a Project that is not On Hold');
        }
        $this->status = ProjectStatus::active();
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
