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
    private $unvettedSpecialists;
    private $approvedSpecialists;
    private $discardedSpecialists;
    private $consultations;

    private function __construct(ClientId $clientId, string $name, \DateTimeInterface $deadline)
    {
        $this->reference = new ProjectReference();
        $this->clientId = $clientId;
        $this->name = $name;
        $this->deadline = $deadline;
        $this->unvettedSpecialists = new SpecialistCollection();
        $this->approvedSpecialists = new SpecialistCollection();
        $this->discardedSpecialists = new SpecialistCollection();
        $this->consultations = new ConsultationCollection();
        $this->status = ProjectStatus::draft();

        EventPublisher::publish(
            new ProjectDraftedEvent(
                (string)$this->reference,
                (string)$this->clientId,
                $this->name,
                date_format($this->deadline, 'c')
            )
        );
    }

    public static function setUp(ClientId $clientId, string $name, \DateTimeInterface $deadline): Project
    {
        return new self($clientId, $name, $deadline);
    }

    public function start(ProjectManagerId $projectManagerId): void
    {
        if ($this->status->isNot(ProjectStatus::DRAFT)) {
            throw new \Exception('Cannot Start a Project that is not in Draft state');
        }
        $this->projectManagerId = $projectManagerId;
        $this->status = ProjectStatus::active();
        EventPublisher::publish(new ProjectStartedEvent((string)$this->reference, (string)$this->projectManagerId));
    }

    public function close(): void
    {
        /** Cannot be event driven, may want to arrange more consultations at any time */
        /** @var Consultation $consultation */
        foreach ($this->consultations->getIterator() as $consultation) {
            if ($consultation->is(ConsultationStatus::OPEN)) {
                throw new \Exception(
                    'Cannot close Project until all open Consultations have been either Confirmed or Discarded'
                );
            }
        }
        $this->status = ProjectStatus::closed();
        EventPublisher::publish(new ProjectClosedEvent((string)$this->reference));
    }

    public function addSpecialist(SpecialistId $specialistId): void
    {
        if ($this->status->isNot(ProjectStatus::ACTIVE)) {
            throw new \Exception('A specialist can only be added to an Active Project');
        }
        if ($this->hasAdded($specialistId)) {
            throw new \Exception('Cannot add a specialist more than once');
        }
        $this->unvettedSpecialists->add($specialistId);
    }

    public function approveSpecialist(SpecialistId $specialistId): void
    {
        if (!$this->unvettedSpecialists->contains($specialistId)) {
            throw new \Exception('Cannot approve a Specialist that is not un-vetted');
        }
        $this->unvettedSpecialists->remove($specialistId);
        $this->approvedSpecialists->add($specialistId);
        EventPublisher::publish(new SpecialistApprovedEvent((string)$this->reference, (string)$specialistId));
    }

    public function discardSpecialist(SpecialistId $specialistId): void
    {
        if (!$this->unvettedSpecialists->contains($specialistId)) {
            throw new \Exception('Cannot discard a Specialist that is not un-vetted');
        }
        $this->unvettedSpecialists->remove($specialistId);
        $this->discardedSpecialists->add($specialistId);
        EventPublisher::publish(new SpecialistDiscardedEvent((string)$this->reference, (string)$specialistId));
    }

    public function scheduleConsultation(SpecialistId $specialistId, \DateTimeInterface $time): ConsultationId
    {
        if ($this->isNot(ProjectStatus::ACTIVE)) {
            throw new \Exception('Cannot schedule a Consultation for a Project that is not active');
        }
        if (!$this->approvedSpecialists->contains($specialistId)) {
            throw new \Exception('Cannot schedule a Consultation with a Specialist that is not approved');
        }
        $consultationId = $this->nextConsultationId();
        $this->consultations->add(
            new Consultation($consultationId, $this->reference, $specialistId, $time)
        );
        EventPublisher::publish(
            new ConsultationScheduledEvent((string)$this->reference, (string)$specialistId, date_format($time, 'c'))
        );
        return $consultationId;
    }

    public function reportConsultation(ConsultationId $consultationId, int $durationMinutes): void
    {
        $this->consultations->get($consultationId)->report($durationMinutes);
    }

    public function discardConsultation(ConsultationId $consultationId): void
    {
        $this->consultations->get($consultationId)->discard();
    }

    public function putOnHold(): void
    {
        if ($this->status->isNot(ProjectStatus::ACTIVE)) {
            throw new \Exception('Cannot put a Project On Hold when it is not Active');
        }
        $this->status = ProjectStatus::onHold();
    }

    public function reactivate(): void
    {
        if ($this->status->isNot(ProjectStatus::ON_HOLD)) {
            throw new \Exception('Cannot Reactivate a Project that is not On Hold');
        }
        $this->status = ProjectStatus::active();
    }

    private function hasAdded(SpecialistId $specialistId): bool
    {
        return $this->unvettedSpecialists->contains($specialistId)
            || $this->approvedSpecialists->contains($specialistId)
            || $this->discardedSpecialists->contains($specialistId);
    }

    private function nextConsultationId(): ConsultationId
    {
        return new ConsultationId(count($this->consultations));
    }

    public function isNot($status): bool
    {
        return !$this->is($status);
    }

    public function is($status): bool
    {
        return $this->status->is($status);
    }

    public function getReference(): ProjectReference
    {
        return $this->reference;
    }
}
