<?php

namespace spec\Mannion007\BestInvestments\Domain\ProjectManagement;

use Mannion007\BestInvestments\Domain\ProjectManagement\ConsultationId;
use Mannion007\BestInvestments\Domain\ProjectManagement\ConsultationScheduledEvent;
use Mannion007\BestInvestments\Domain\ProjectManagement\Project;
use Mannion007\BestInvestments\Domain\ProjectManagement\ClientId;
use Mannion007\BestInvestments\Domain\ProjectManagement\ProjectDraftedEvent;
use Mannion007\BestInvestments\Domain\ProjectManagement\ProjectManagerId;
use Mannion007\BestInvestments\Domain\ProjectManagement\ProjectStatus;
use Mannion007\BestInvestments\Domain\ProjectManagement\SpecialistApprovedEvent;
use Mannion007\BestInvestments\Domain\ProjectManagement\SpecialistId;
use Mannion007\BestInvestments\Domain\ProjectManagement\ProjectClosedEvent;
use Mannion007\BestInvestments\Event\EventPublisher;
use Mannion007\BestInvestments\Event\InMemoryHandler;
use PhpSpec\ObjectBehavior;

/**
 * Class TimeIncrementSpec
 * @package spec\Mannion007\BestInvestments\Domain\ProjectManagement
 * @mixin Project
 */
class ProjectSpec extends ObjectBehavior
{
    /** @var InMemoryHandler */
    private $handler;

    function let()
    {
        /** Find before suite annotation to improve this */
        $this->handler = new InMemoryHandler();
        EventPublisher::registerHandler($this->handler);

        $clientId = ClientId::fromExisting('test123');
        $name = 'test-project';
        $deadline = (new \DateTime())->modify('+1 year');
        $this->beConstructedThrough('setUp', [$clientId, $name, $deadline]);

        $status = new \ReflectionProperty($this->getWrappedObject(), 'status');
        $status->setAccessible(true);
        $status->setValue($this->getWrappedObject(), ProjectStatus::active());
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Project::class);
        if (!$this->handler->hasPublished(ProjectDraftedEvent::EVENT_NAME)) {
            throw new \Exception('An event should have been published when the Project was Drafted');
        }
    }

    function it_cannot_start_when_the_project_is_not_drafted()
    {
        $this->shouldThrow(new \Exception('Cannot Start a Project that is not in Draft state'))
            ->during('start', [ProjectManagerId::fromExisting('test-project-manager-id')]);
    }

    function it_starts_when_the_project_is_drafted()
    {
        $status = new \ReflectionProperty($this->getWrappedObject(), 'status');
        $status->setAccessible(true);
        $status->setValue($this->getWrappedObject(), ProjectStatus::draft());
        $this->start(ProjectManagerId::fromExisting('test-project-manager-id'));
    }

    function it_cannot_close_when_it_has_an_open_consultation()
    {
        $this->addSpecialist(SpecialistId::fromExisting('test'));
        $this->approveSpecialist(SpecialistId::fromExisting('test'));
        $this->scheduleConsultation(SpecialistId::fromExisting('test'), (new \DateTime())->modify('+1 week'));
        $this->shouldThrow(
            new \Exception('Cannot close Project until all open Consultations have been either Confirmed or Discarded')
        )->during('close');
    }

    function it_closes()
    {
        $this->addSpecialist(SpecialistId::fromExisting('test'));
        $this->approveSpecialist(SpecialistId::fromExisting('test'));
        $this->scheduleConsultation(SpecialistId::fromExisting('test'), (new \DateTime())->modify('+1 week'));
        $this->reportConsultation(new ConsultationId(0), 60);
        $this->close();
        if (!$this->handler->hasPublished(ProjectClosedEvent::EVENT_NAME)) {
            throw new \Exception('An event should have been published when the Project was Closed');
        }
    }

    function it_cannot_add_a_specialist_when_the_project_is_not_active()
    {
        $status = new \ReflectionProperty($this->getWrappedObject(), 'status');
        $status->setAccessible(true);
        $status->setValue($this->getWrappedObject(), ProjectStatus::draft());
        $this->shouldThrow()->during('addSpecialist', [SpecialistId::fromExisting('test')]);
    }

    function it_cannot_add_specialist_more_than_once()
    {
        $this->addSpecialist(SpecialistId::fromExisting('test'));
        $this->shouldThrow(new \Exception('Cannot add a specialist more than once'))
            ->during('addSpecialist', [SpecialistId::fromExisting('test')]);
    }

    function it_adds_a_specialist()
    {
        $this->addSpecialist(SpecialistId::fromExisting('test'));
    }

    function it_cannot_approve_a_specialist_that_has_not_been_added()
    {
        $this->shouldThrow(new \Exception('Cannot approve a Specialist that is not un-vetted'))
            ->during('approveSpecialist', [SpecialistId::fromExisting('test')]);
    }

    function it_cannot_approve_a_specialist_that_is_not_unvetted()
    {
        $this->addSpecialist(SpecialistId::fromExisting('test'));
        $this->approveSpecialist(SpecialistId::fromExisting('test'));
        $this->shouldThrow(new \Exception('Cannot approve a Specialist that is not un-vetted'))
            ->during('approveSpecialist', [SpecialistId::fromExisting('test')]);
    }

    function it_approves_specialists()
    {
        $this->addSpecialist(SpecialistId::fromExisting('test'));
        $this->approveSpecialist(SpecialistId::fromExisting('test'));
        if (!$this->handler->hasPublished(SpecialistApprovedEvent::EVENT_NAME)) {
            throw new \Exception('An event should have been published when the Specialist was Approved');
        }
    }

    function it_cannot_discard_specialists_that_have_not_been_added()
    {
        $this->shouldThrow(new \Exception('Cannot discard a Specialist that is not un-vetted'))
            ->during('discardSpecialist', [SpecialistId::fromExisting('test')]);
    }

    function it_cannot_discard_specialists_that_are_not_unvetted()
    {
        $this->addSpecialist(SpecialistId::fromExisting('test'));
        $this->approveSpecialist(SpecialistId::fromExisting('test'));
        $this->shouldThrow(new \Exception('Cannot discard a Specialist that is not un-vetted'))
            ->during('discardSpecialist', [SpecialistId::fromExisting('test')]);
    }

    function it_discards_specialists()
    {
        $this->addSpecialist(SpecialistId::fromExisting('test'));
        $this->discardSpecialist(SpecialistId::fromExisting('test'));
    }

    function it_cannot_schedule_a_consultaiton_when_it_is_not_active()
    {
        $status = new \ReflectionProperty($this->getWrappedObject(), 'status');
        $status->setAccessible(true);
        $status->setValue($this->getWrappedObject(), ProjectStatus::draft());

        $this->shouldThrow(new \Exception('Cannot schedule a Consultation for a Project that is not active'))
            ->during('scheduleConsultation', [SpecialistId::fromExisting('test'), new \DateTime()]);
    }

    function it_cannot_schedule_a_consultation_with_a_specialist_that_has_not_been_approved()
    {
        $this->addSpecialist(SpecialistId::fromExisting('test'));
        $this->shouldThrow(new \Exception('Cannot schedule a Consultation with a Specialist that is not approved'))
            ->during('scheduleConsultation', [SpecialistId::fromExisting('test'), new \DateTime()]);
    }

    function it_schedules_consultations()
    {
        $this->addSpecialist(SpecialistId::fromExisting('test'));
        $this->approveSpecialist(SpecialistId::fromExisting('test'));
        $this->scheduleConsultation(SpecialistId::fromExisting('test'), new \DateTime());
        if (!$this->handler->hasPublished(ConsultationScheduledEvent::EVENT_NAME)) {
            throw new \Exception('An event should have been published when the Consultation was Scheduled');
        }
    }

    function it_reports_consultations()
    {
        $this->addSpecialist(SpecialistId::fromExisting('test'));
        $this->approveSpecialist(SpecialistId::fromExisting('test'));
        $consultationId = $this->scheduleConsultation(SpecialistId::fromExisting('test'), new \DateTime());
        $this->reportConsultation($consultationId, 60);
    }

    function it_discards_consultations()
    {
        $this->addSpecialist(SpecialistId::fromExisting('test'));
        $this->approveSpecialist(SpecialistId::fromExisting('test'));
        $consultationId = $this->scheduleConsultation(SpecialistId::fromExisting('test'), new \DateTime());
        $this->discardConsultation($consultationId);
    }

    function it_cannot_be_put_on_hold_when_it_is_not_active()
    {
        $status = new \ReflectionProperty($this->getWrappedObject(), 'status');
        $status->setAccessible(true);
        $status->setValue($this->getWrappedObject(), ProjectStatus::draft());
        $this->shouldThrow(new \Exception('Cannot put a Project On Hold when it is not Active'))
            ->during('putOnHold');
    }

    function it_can_be_put_on_hold()
    {
        $this->putOnHold();
    }

    function it_cannot_be_reactivated_when_it_is_not_on_hold()
    {
        $this->shouldThrow(new \Exception('Cannot Reactivate a Project that is not On Hold'))
            ->during('reactivate');
    }
}
