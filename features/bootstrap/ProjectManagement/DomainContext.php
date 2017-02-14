<?php

namespace Mannion007\BestInvestmentsBehat\ProjectManagement;

use Behat\Behat\Context\Context;
use Mannion007\BestInvestments\ProjectManagement\Domain\ConsultationId;
use Mannion007\BestInvestments\ProjectManagement\Domain\ClientId;
use Mannion007\BestInvestments\ProjectManagement\Domain\ConsultationCollection;
use Mannion007\BestInvestments\ProjectManagement\Domain\ConsultationScheduledEvent;
use Mannion007\BestInvestments\ProjectManagement\Domain\ConsultationStatus;
use Mannion007\BestInvestments\ProjectManagement\Domain\Money;
use Mannion007\BestInvestments\ProjectManagement\Domain\PotentialSpecialist;
use Mannion007\BestInvestments\ProjectManagement\Domain\Project;
use Mannion007\BestInvestments\ProjectManagement\Domain\ProjectClosedEvent;
use Mannion007\BestInvestments\ProjectManagement\Domain\ProjectDraftedEvent;
use Mannion007\BestInvestments\ProjectManagement\Domain\ProjectStartedEvent;
use Mannion007\BestInvestments\ProjectManagement\Domain\Specialist;
use Mannion007\BestInvestments\ProjectManagement\Domain\SpecialistApprovedEvent;
use Mannion007\BestInvestments\ProjectManagement\Domain\SpecialistDiscardedEvent;
use Mannion007\BestInvestments\ProjectManagement\Domain\ProjectManagerId;
use Mannion007\BestInvestments\ProjectManagement\Domain\ProjectStatus;
use Mannion007\BestInvestments\ProjectManagement\Domain\SpecialistCollection;
use Mannion007\BestInvestments\ProjectManagement\Domain\SpecialistId;
use Mannion007\BestInvestments\ProjectManagement\Domain\SpecialistPutOnListEvent;
Use Mannion007\BestInvestments\ProjectManagement\Infrastructure\EventPublisher\InMemoryEventPublisher;
use Mannion007\BestInvestments\Event\EventPublisher;

/**
 * Defines application features from the specific context.
 */
class DomainContext implements Context
{
    /** @var InMemoryEventPublisher */
    private $eventPublisher;

    /** @var ClientId */
    private $clientId;

    /** @var ProjectManagerId */
    private $projectManagerId;

    /** @var Project */
    private $project;

    /** @var SpecialistId */
    private $specialistId;

    /** @var ConsultationId */
    private $consultationId;

    /** @var PotentialSpecialist */
    private $potentialSpecialist;

    /** @var Specialist */
    private $specialist;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->eventPublisher = new InMemoryEventPublisher();
        EventPublisher::registerPublisher($this->eventPublisher);

        $this->clientId = ClientId::fromExisting('test-client-123');
        $this->projectManagerId = ProjectManagerId::fromExisting('project-manager-123');
        $this->specialistId = SpecialistId::fromExisting('test-specialist-id');
    }

    /**
     * @Given I have a Client
     */
    public function iHaveAClient()
    {
    }

    /**
     * @Given I have a Specialist
     */
    public function iHaveASpecialist()
    {
    }

    /**
     * @Given I have a Project Manager
     */
    public function iHaveAProjectManager()
    {
    }

    /**
     * @Given The Specialist has not been added to the Project
     */
    public function theSpecialistHasNotBeenAddedToTheProject()
    {
        $reflected = new \ReflectionMethod($this->project, 'hasAdded');
        $reflected->setAccessible(true);
        if ($reflected->invoke($this->project, $this->specialistId)) {
            throw new \Exception('The Specialist has been added to the Project');
        }
    }

    /**
     * @Given I have a drafted Project
     */
    public function iHaveADraftedProject()
    {
        $this->project = Project::setUp($this->clientId, 'My Lovely Project', new \DateTime('+1 year'));
    }

    /**
     * @Given I have an active Project
     */
    public function iHaveAnActiveProject()
    {
        $this->project = Project::setUp($this->clientId, 'My Lovely Project', new \DateTime('+1 year'));
        $this->project->start($this->projectManagerId);
    }

    /**
     * @Given The Project has no open Consultations
     */
    public function theProjectHasNoOpenConsultations()
    {
        $reflected = new \ReflectionMethod($this->project, 'hasAnOpenConsultation');
        $reflected->setAccessible(true);
        if ($reflected->invoke($this->project, 'hasAnOpenConsultation')) {
            throw new \Exception('The Project has an open Consultation');
        }
    }


    /**
     * @Given I have an on hold Project
     */
    public function iHaveAnOnHoldProject()
    {
        $this->project = Project::setUp($this->clientId, 'My Lovely Project', new \DateTime('+1 year'));
        $this->project->start($this->projectManagerId);
        $this->project->putOnHold();
    }

    /**
     * @Given The Project has an open Consultation
     */
    public function theProjectHasAnOpenConsultation()
    {
        $this->project->addSpecialist($this->specialistId);
        $this->project->approveSpecialist($this->specialistId);
        $this->consultationId = $this->project->scheduleConsultation($this->specialistId, new \DateTime('+1 year'));
    }

    /**
     * @Given I have a Potential Specialist
     */
    public function iHaveAPotentialSpecialist()
    {
    }

    /**
     * @When I report the Consultation
     */
    public function iReportTheConsultation()
    {
        $this->project->reportConsultation($this->consultationId, 60);
    }

    /**
     * @When I discard the Consultation
     */
    public function iDiscardTheConsultation()
    {
        $this->project->discardConsultation($this->consultationId);
    }

    /**
     * @Given The project has an un-vetted Specialist
     */
    public function theProjectHasAnUnvettedSpecialist()
    {
        $this->project->addSpecialist($this->specialistId);
    }

    /**
     * @Given The Specialist is approved for the Project
     */
    public function theSpecialistIsApprovedForTheProject()
    {
        $this->project->addSpecialist($this->specialistId);
        $this->project->approveSpecialist($this->specialistId);
    }

    /**
     * @When I Set Up a Project for the Client with the name :name and the deadline :deadline
     */
    public function iSetUpAProjectForTheClientWithTheNameAndTheDeadline($name, $deadline)
    {
        $deadline = \DateTime::createFromFormat('Y-m-d', $deadline);
        $this->project = Project::setUp($this->clientId, $name, $deadline);
        $this->eventShouldHaveBeenPublishedNamed(ProjectDraftedEvent::EVENT_NAME);
    }

    /**
     * @When I assign the Project Manager to the Project
     */
    public function iAssignTheProjectManagerToTheProject()
    {
        $this->project->start($this->projectManagerId);
        $this->eventShouldHaveBeenPublishedNamed(ProjectStartedEvent::EVENT_NAME);
    }

    /**
     * @When I close the Project
     */
    public function iCloseTheProject()
    {
        $this->project->close();
        $this->eventShouldHaveBeenPublishedNamed(ProjectClosedEvent::EVENT_NAME);
    }

    /**
     * @When The Project is put on hold
     */
    public function TheProjectIsPutOnHold()
    {
        $this->project->putOnHold();
    }

    /**
     * @When The Project is reactivated
     */
    public function TheProjectIsReactivated()
    {
        $this->project->reactivate();
    }

    /**
     * @When I add the Specialist to the Project
     */
    public function iAddTheSpecialistToTheProject()
    {
        $this->project->addSpecialist($this->specialistId);
    }

    /**
     * @When I approve the Specialist
     */
    public function iApproveTheSpecialist()
    {
        $this->project->approveSpecialist($this->specialistId);
        $this->eventShouldHaveBeenPublishedNamed(SpecialistApprovedEvent::EVENT_NAME);
    }

    /**
     * @When I discard the Specialist
     */
    public function iDiscardTheSpecialist()
    {
        $this->project->discardSpecialist($this->specialistId);
        $this->eventShouldHaveBeenPublishedNamed(SpecialistDiscardedEvent::EVENT_NAME);
    }

    /**
     * @Then I should have a Draft of a Project
     */
    public function iShouldHaveADraftOfAProject()
    {
        if ($this->project->isNot(ProjectStatus::DRAFT)) {
            throw new \Exception('The project is not drafted');
        }
    }

    /**
     * @Then The Project should be marked as active
     */
    public function theProjectShouldBeMarkedAsActive()
    {
        if ($this->project->isNot(ProjectStatus::ACTIVE)) {
            throw new \Exception('The Project is not active');
        }
    }

    /**
     * @Then The Project should be marked as closed
     */
    public function theProjectShouldBeMarkedAsClosed()
    {
        if ($this->project->isNot(ProjectStatus::CLOSED)) {
            throw new \Exception('The Project is not closed');
        }
    }

    /**
     * @Then Specialists can be added to the Project
     */
    public function specialistsCanBeAddedToTheProject()
    {
        $this->project->addSpecialist(SpecialistId::fromExisting('test-specialist-id'));
    }

    /**
     * @Then The Specialist should be added and marked as un-vetted
     */
    public function theSpecialistShouldBeAddedAndMarkedAsUnvetted()
    {
        $reflectionProperty = new \ReflectionProperty($this->project, 'unvettedSpecialists');
        $reflectionProperty->setAccessible(true);
        /** @var SpecialistCollection */
        $unvettedSpecialists = $reflectionProperty->getValue($this->project);
        if (!$unvettedSpecialists->contains($this->specialistId)) {
            throw new \Exception('The Specialist is not marked as un-vetted');
        }
    }

    /**
     * @Then The Specialist should be marked as approved
     */
    public function theSpecialistShouldBeMarkedAsApproved()
    {
        $reflectionProperty = new \ReflectionProperty($this->project, 'approvedSpecialists');
        $reflectionProperty->setAccessible(true);
        /** @var SpecialistCollection */
        $approvedSpecialists = $reflectionProperty->getValue($this->project);
        if (!$approvedSpecialists->contains($this->specialistId)) {
            throw new \Exception('The Specialist is not marked as approved');
        }
    }

    /**
     * @Then The Specialist should be marked as discarded
     */
    public function theSpecialistShouldBeMarkedAsDiscarded()
    {
        $reflectionProperty = new \ReflectionProperty($this->project, 'discardedSpecialists');
        $reflectionProperty->setAccessible(true);
        /** @var SpecialistCollection */
        $discardedSpecialists = $reflectionProperty->getValue($this->project);
        if (!$discardedSpecialists->contains($this->specialistId)) {
            throw new \Exception('The Specialist is not marked as discarded');
        }
    }

    /**
     * @When I schedule a Consultation with the Specialist on the Project
     */
    public function iScheduleAConsultationWithTheSpecialistOnTheProject()
    {
        $this->consultationId = $this->project->scheduleConsultation($this->specialistId, new \DateTime('+1 week'));
    }

    /**
     * @When I add the Specialist to the list
     */
    public function iAddTheSpecialistToTheList()
    {
        $this->potentialSpecialist = PotentialSpecialist::putOnList(
            ProjectManagerId::fromExisting('test-project-manager-id'),
            'Test Specialist',
            'This is just a test'
        );
        $this->eventShouldHaveBeenPublishedNamed(SpecialistPutOnListEvent::EVENT_NAME);
    }

    /**
     * @Then The Consultation should be scheduled with the Specialist on the Project
     */
    public function theConsultationShouldBeScheduledWithTheSpecialistOnTheProject()
    {
        $reflected = new \ReflectionProperty($this->project, 'consultations');
        $reflected->setAccessible(true);
        /** @var ConsultationCollection */
        $consultations = $reflected->getValue($this->project);
        if (!$consultations->contains($this->consultationId)) {
            throw new \Exception('The Consultation has not been scheduled on the Project');
        }
        $this->eventShouldHaveBeenPublishedNamed(ConsultationScheduledEvent::EVENT_NAME);
    }

    /**
     * @Then The Project should be marked as on hold
     */
    public function theProjectShouldBeMarkedAsOnHold()
    {
        if ($this->project->isNot(ProjectStatus::ON_HOLD)) {
            throw new \Exception('The Project is not on hold');
        }
    }

    /**
     * @Then The Consultation should be marked as confirmed
     */
    public function theConsultationShouldBeMarkedAsConfirmed()
    {
        $this->theConsultationShouldBeMarkedAs(ConsultationStatus::CONFIRMED);
    }

    /**
     * @Then The Consultation should be marked as discarded
     */
    public function theConsultationShouldBeMarkedAsDiscarded()
    {
        $this->theConsultationShouldBeMarkedAs(ConsultationStatus::DISCARDED);
    }

    private function theConsultationShouldBeMarkedAs(string $expected)
    {
        $reflected = new \ReflectionProperty($this->project, 'consultations');
        $reflected->setAccessible(true);
        /** @var ConsultationCollection $consultations */
        $consultations = $reflected->getValue($this->project);
        if ($consultations->get($this->consultationId)->isNot($expected)) {
            throw new \Exception(sprintf('The Consultation is not marked as %s', $expected));
        }
    }

    /**
     * @Then I should have a Potential Specialist
     */
    public function iShouldHaveAPotentialSpecialist()
    {
        if (is_null($this->potentialSpecialist)) {
            throw new \Exception('I do not have a Potential Specialist');
        }
    }

    /**
     * @Given I have put a Potential Specialist on the list
     */
    public function iHavePutAPotentialSpecialistOnTheList()
    {
        $this->potentialSpecialist =
            PotentialSpecialist::putOnList(
                $this->projectManagerId,
                'Test Specialist',
                'This is just a test'
            );
    }

    /**
     * @When The Potential Specialist registers
     */
    public function thePotentialSpecialistRegisters()
    {
        $this->specialist = $this->potentialSpecialist->register(new Money(100));
    }

    /**
     * @Then The Specialist should be available for me to push to Clients
     */
    public function theSpecialistShouldBeAvailableToPushToClients()
    {
        if (is_null($this->specialist)) {
            throw new \Exception('I do not have a Specialist available to push to clients');
        }
    }

    private function eventShouldHaveBeenPublishedNamed(string $eventName)
    {
        if ($this->eventPublisher->hasNotPublished($eventName)) {
            throw new \Exception(
                'The event has not been published'
            );
        }
    }
}
