<?php

namespace Mannion007\BestInvestments\Domain\ProjectManagement;

use Mannion007\BestInvestments\Event\EventPublisher;

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
    /** @var SpecialistCollection  */
    private $consultations = [];

    private function __construct(ClientId $clientId, string $name, \DateTime $deadline)
    {
        $this->reference = new ProjectReference();
        $this->clientId = $clientId;
        $this->name = $name;
        $this->deadline = $deadline;
        $this->specialists = new SpecialistCollection();
        $this->consultations = new ConsultationCollection();
        $this->status = ProjectStatus::draft();

        EventPublisher::publish(new ProjectDrafted($this->reference, $this->clientId, $this->name, $this->deadline));
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

        EventPublisher::publish(new ProjectStarted($this->reference, $this->projectManagerId));
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

        EventPublisher::publish(new ProjectClosed($this->reference));
    }

    public function addSpecialist(SpecialistId $specialistId)
    {
        if ($this->status->isNot(ProjectStatus::ACTIVE)) {
            throw new \DomainException('A specialist can only be added to an Active Project');
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

        EventPublisher::publish(new SpecialistApproved($this->reference, $specialistId));
    }

    public function discardSpecialist(SpecialistId $specialistId)
    {
        if ($this->specialists[(string)$specialistId]->isNot(SpecialistRecommendation::UNVETTED)) {
            throw new \DomainException('Potential specialist is not unvetted');
        }
        $this->specialists[(string)$specialistId] = SpecialistRecommendation::discarded();

        EventPublisher::publish(new SpecialistDiscarded($this->reference, $specialistId));
    }

    public function scheduleConsultation(SpecialistId $specialistId, \DateTime $time)
    {
        if ($this->isNot(ProjectStatus::ACTIVE)) {
            throw new \DomainException('Cannot schedule a Consultation for a Project that is not active');
        }
        if ($this->specialists[(string)$specialistId]->isNot(SpecialistRecommendation::APPROVED)) {
            throw new \DomainException('A consultation can only be scheduled with an approved Specialist');
        }
        $consultationId = $this->nextConsultationId();
        $this->consultations[(string)$consultationId]
            = new Consultation($consultationId, $this->reference, $specialistId, $time);

        EventPublisher::publish(new ConsultationScheduled($this->reference, $specialistId, $time));
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

    public function putOnHold()
    {
        /** Need to enforce this, or if not on hold just do nothing? */
        if ($this->status->isNot(ProjectStatus::ACTIVE)) {
            throw new \DomainException('Cannot put a Project On Hold when it is not Active');
        }
        $this->status = ProjectStatus::onHold();
    }

    public function reactivate()
    {
        if ($this->status->isNot(ProjectStatus::ON_HOLD)) {
            throw new \DomainException('Cannot Reactivate a Project that is not On Hold');
        }
        $this->status = ProjectStatus::active();
    }

    public function getReference() : ProjectReference
    {
        return $this->reference;
    }

    private function nextConsultationId() : ConsultationId
    {
        return new ConsultationId(count($this->consultations));
    }

    public function isNot($status) : bool
    {
        return !$this->is($status);
    }

    public function is($status) : bool
    {
        return $this->status->is($status);
    }
}
