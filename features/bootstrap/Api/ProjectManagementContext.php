<?php

namespace Mannion007\BestInvestmentsBehat\Api;

use Behat\Behat\Context\Context;
use Pavlakis\Slim\Behat\Context\App;
use Pavlakis\Slim\Behat\Context\KernelAwareContext;
use \GuzzleHttp\Client;
use Mannion007\BestInvestments\Domain\ProjectManagement\ConsultationScheduledEvent;
use Mannion007\BestInvestments\Domain\ProjectManagement\ProjectClosedEvent;
use Mannion007\BestInvestments\Domain\ProjectManagement\ProjectDraftedEvent;
use Mannion007\BestInvestments\Domain\ProjectManagement\ProjectStartedEvent;
use Mannion007\BestInvestments\Domain\ProjectManagement\SpecialistApprovedEvent;
use Mannion007\BestInvestments\Domain\ProjectManagement\SpecialistDiscardedEvent;

/**
 * Defines application features from the specific context.
 */
class ProjectManagementContext implements Context, KernelAwareContext
{
    use App;

    private $clientId;
    private $projectManagerId;
    private $projectReference;
    private $specialistId;
    private $consultationId;
    private $guzzle;

    public function __construct()
    {
        $this->clientId = 'test-client-123';
        $this->projectManagerId = 'project-manager-123';
        $this->specialistId = 'test-specialist-id';
        $this->guzzle = new Client();
    }

    /** @BeforeScenario */
    public function before()
    {
        $this->app->getContainer()->get('redis_project_repository')->purge();
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
    }

    /**
     * @Given I have a drafted Project
     */
    public function iHaveADraftedProject()
    {
        $response = $this->guzzle->post(
            sprintf('%s/project/set-up', $this->app->getContainer()->get('base_uri')),
            [
                'form_params' => [
                    'client-id' => $this->clientId,
                    'name' => 'What could go wrong?',
                    'deadline' => '2020-05-25'
                ]
            ]
        );
        $decodedResponse = json_decode($response->getBody()->getContents());
        $this->projectReference = $decodedResponse->project_reference;
    }

    /**
     * @Given I have an active Project
     */
    public function iHaveAnActiveProject()
    {
        $response = $this->guzzle->post(
            sprintf('%s/project/set-up', $this->app->getContainer()->get('base_uri')),
            [
                'form_params' => [
                    'client-id' => $this->clientId,
                    'name' => 'What could go wrong?',
                    'deadline' => '2020-05-25'
                ]
            ]
        );

        $decodedResponse = json_decode($response->getBody()->getContents());
        $this->projectReference = $decodedResponse->project_reference;

        $this->guzzle->post(
            sprintf('%s/project/start', $this->app->getContainer()->get('base_uri')),
            [
                'form_params' => [
                    'project-reference' => $this->projectReference,
                    'project-manager-id' => $this->projectManagerId
                ]
            ]
        );
    }

    /**
     * @Given The Project has no open Consultations
     */
    public function theProjectHasNoOpenConsultations()
    {
        //Read side...
    }

    /**
     * @Given I have an on hold Project
     */
    public function iHaveAnOnHoldProject()
    {
        $response = $this->guzzle->post(
            sprintf('%s/project/set-up', $this->app->getContainer()->get('base_uri')),
            [
                'form_params' => [
                    'client-id' => $this->clientId,
                    'name' => 'What could go wrong?',
                    'deadline' => '2020-05-25'
                ]
            ]
        );

        $decodedResponse = json_decode($response->getBody()->getContents());
        $this->projectReference = $decodedResponse->project_reference;

        $this->guzzle->post(
            sprintf('%s/project/start', $this->app->getContainer()->get('base_uri')),
            [
                'form_params' => [
                    'project-reference' => $this->projectReference,
                    'project-manager-id' => $this->projectManagerId
                ]
            ]
        );
        $this->guzzle->post(
            sprintf('%s/project/put-on-hold', $this->app->getContainer()->get('base_uri')),
            [
                'form_params' => [
                    'project-reference' => $this->projectReference
                ]
            ]
        );
    }

    /**
     * @Given The Project has an open Consultation
     */
    public function theProjectHasAnOpenConsultation()
    {
        $this->guzzle->post(
            sprintf('%s/project/add-specialist', $this->app->getContainer()->get('base_uri')),
            [
                'form_params' => [
                    'project-reference' => $this->projectReference,
                    'specialist-id' => $this->specialistId
                ]
            ]
        );
        $this->guzzle->post(
            sprintf('%s/project/approve-specialist', $this->app->getContainer()->get('base_uri')),
            [
                'form_params' => [
                    'project-reference' => $this->projectReference,
                    'specialist-id' => $this->specialistId
                ]
            ]
        );
        $response = $this->guzzle->post(
            sprintf('%s/project/schedule-consultation', $this->app->getContainer()->get('base_uri')),
            [
                'form_params' => [
                    'project-reference' => $this->projectReference,
                    'specialist-id' => $this->specialistId,
                    'time' => '2020-05-15'
                ]
            ]
        );
        $decodedResponse = json_decode($response->getBody()->getContents());
        $this->consultationId = $decodedResponse->consultation_id;
    }

    /**
     * @When I report the Consultation
     */
    public function iReportTheConsultation()
    {
        $this->guzzle->post(
            sprintf('%s/project/report-consultation', $this->app->getContainer()->get('base_uri')),
            [
                'form_params' => [
                    'project-reference' => $this->projectReference,
                    'consultation-id' => $this->consultationId,
                    'duration' => 60
                ]
            ]
        );
    }

    /**
     * @When I discard the Consultation
     */
    public function iDiscardTheConsultation()
    {
        $this->guzzle->post(
            sprintf('%s/project/discard-consultation', $this->app->getContainer()->get('base_uri')),
            [
                'form_params' => [
                    'project-reference' => $this->projectReference,
                    'consultation-id' => $this->consultationId
                ]
            ]
        );
    }

    /**
     * @Given The project has an un-vetted Specialist
     */
    public function theProjectHasAnUnvettedSpecialist()
    {
        $this->guzzle->post(
            sprintf('%s/project/add-specialist', $this->app->getContainer()->get('base_uri')),
            [
                'form_params' => [
                    'project-reference' => $this->projectReference,
                    'specialist-id' => $this->specialistId
                ]
            ]
        );
    }

    /**
     * @Given The Specialist is approved for the Project
     */
    public function theSpecialistIsApprovedForTheProject()
    {
        $this->guzzle->post(
            sprintf('%s/project/add-specialist', $this->app->getContainer()->get('base_uri')),
            [
                'form_params' => [
                    'project-reference' => $this->projectReference,
                    'specialist-id' => $this->specialistId
                ]
            ]
        );
        $this->guzzle->post(
            sprintf('%s/project/approve-specialist', $this->app->getContainer()->get('base_uri')),
            [
                'form_params' => [
                    'project-reference' => $this->projectReference,
                    'specialist-id' => $this->specialistId
                ]
            ]
        );
    }

    /**
     * @When I Set Up a Project for the Client with the name :name and the deadline :deadline
     */
    public function iSetUpAProjectForTheClientWithTheNameAndTheDeadline(string $name, string $deadline)
    {
        $response = $this->guzzle->post(
            sprintf('%s/project/set-up', $this->app->getContainer()->get('base_uri')),
            [
                'form_params' => [
                    'client-id' => $this->clientId,
                    'name' => $name,
                    'deadline' => $deadline
                ]
            ]
        );
        $decodedResponse = json_decode($response->getBody()->getContents());
        $this->projectReference = $decodedResponse->project_reference;
    }

    /**
     * @When I assign the Project Manager to the Project
     */
    public function iAssignTheProjectManagerToTheProject()
    {
        $this->guzzle->post(
            sprintf('%s/project/start', $this->app->getContainer()->get('base_uri')),
            [
                'form_params' => [
                    'project-reference' => $this->projectReference,
                    'project-manager-id' => $this->projectManagerId
                ]
            ]
        );
    }

    /**
     * @When I close the Project
     */
    public function iCloseTheProject()
    {
        $this->guzzle->post(
            sprintf('%s/project/close', $this->app->getContainer()->get('base_uri')),
            [
                'form_params' => [
                    'project-reference' => $this->projectReference
                ]
            ]
        );
    }

    /**
     * @When I put the Project on hold
     */
    public function iPutTheProjectOnHold()
    {
        $this->guzzle->post(
            sprintf('%s/project/put-on-hold', $this->app->getContainer()->get('base_uri')),
            [
                'form_params' => [
                    'project-reference' => $this->projectReference
                ]
            ]
        );
    }

    /**
     * @When I reactivate the Project
     */
    public function iReactivateTheProject()
    {
        $this->guzzle->post(
            sprintf('%s/project/reactivate', $this->app->getContainer()->get('base_uri')),
            [
                'form_params' => [
                    'project-reference' => $this->projectReference
                ]
            ]
        );
    }

    /**
     * @When I add the Specialist to the Project
     */
    public function iAddTheSpecialistToTheProject()
    {
        $this->guzzle->post(
            sprintf('%s/project/add-specialist', $this->app->getContainer()->get('base_uri')),
            [
                'form_params' => [
                    'project-reference' => $this->projectReference,
                    'specialist-id' => $this->specialistId
                ]
            ]
        );
    }

    /**
     * @When I approve the Specialist
     */
    public function iApproveTheSpecialist()
    {
        $this->guzzle->post(
            sprintf('%s/project/approve-specialist', $this->app->getContainer()->get('base_uri')),
            [
                'form_params' => [
                    'project-reference' => $this->projectReference,
                    'specialist-id' => $this->specialistId
                ]
            ]
        );
    }

    /**
     * @When I discard the Specialist
     */
    public function iDiscardTheSpecialist()
    {
        $this->guzzle->post(
            sprintf('%s/project/discard-specialist', $this->app->getContainer()->get('base_uri')),
            [
                'form_params' => [
                    'project-reference' => $this->projectReference,
                    'specialist-id' => $this->specialistId
                ]
            ]
        );
    }

    /**
     * @Then I should have a Draft of a Project
     */
    public function iShouldHaveADraftOfAProject()
    {
        //Read side
    }

    /**
     * @Then The Project should be marked as active
     */
    public function theProjectShouldBeMarkedAsActive()
    {
        //Read side
    }

    /**
     * @Then The Project should be marked as closed
     */
    public function theProjectShouldBeMarkedAsClosed()
    {
        //Read side
    }

    /**
     * @Then Specialists can be added to the Project
     */
    public function specialistsCanBeAddedToTheProject()
    {
        $this->guzzle->post(
            sprintf('%s/project/add-specialist', $this->app->getContainer()->get('base_uri')),
            [
                'form_params' => [
                    'project-reference' => $this->projectReference,
                    'specialist-id' => $this->specialistId
                ]
            ]
        );
    }

    /**
     * @Then The Specialist should be added and marked as un-vetted
     */
    public function theSpecialistShouldBeAddedAndMarkedAsUnvetted()
    {
        //Read side...
    }

    /**
     * @Then The Specialist should be marked as approved
     */
    public function theSpecialistShouldBeMarkedAsApproved()
    {
        //Read side...
    }

    /**
     * @Then The Specialist should be marked as discarded
     */
    public function theSpecialistShouldBeMarkedAsDiscarded()
    {
        //Read side...
    }

    /**
     * @When I schedule a Consultation with the Specialist on the Project
     */
    public function iScheduleAConsultationWithTheSpecialistOnTheProject()
    {
        //Add time?
        $this->guzzle->post(
            sprintf('%s/project/schedule-consultation', $this->app->getContainer()->get('base_uri')),
            [
                'form_params' => [
                    'project-reference' => $this->projectReference,
                    'specialist-id' => $this->specialistId,
                    'time' => '2020-05-15'
                ]
            ]
        );
    }

    /**
     * @Then The Consultation should be scheduled with the Specialist on the Project
     */
    public function theConsultationShouldBeScheduledWithTheSpecialistOnTheProject()
    {
        //Read side...
    }

    /**
     * @Then The Project should be marked as on hold
     */
    public function theProjectShouldBeMarkedAsOnHold()
    {
        //Read side...
    }

    /**
     * @Then A Senior Project Manager should be notified that the Project has been drafted
     */
    public function aSeniorProjectManagerShouldBeNotifiedThatTheProjectHasBeenDrafted()
    {
        $eventHandler = $this->app->getContainer()->get('redis_handler');
        if ($eventHandler->hasNotPublished(ProjectDraftedEvent::EVENT_NAME)) {
            throw new \Exception('A Senior Project Manager has not been notified that the Project has been drafted');
        }
    }

    /**
     * @Then The Invoicing Team should be notified that the Project has closed
     */
    public function theInvoicingTeamShouldBeNotifiedThatTheProjectHasClosed()
    {
        $eventHandler = $this->app->getContainer()->get('redis_handler');
        if ($eventHandler->hasNotPublished(ProjectClosedEvent::EVENT_NAME)) {
            throw new \Exception('The Invoicing Team has not been notified the Project has closed');
        }
    }

    /**
     * @Then The Project Management team should be notified that the Specialist has been approved
     */
    public function theProjectManagementTeamShouldBeNotifiedThatTheSpecialistHasBeenApproved()
    {
        $eventHandler = $this->app->getContainer()->get('redis_handler');
        if ($eventHandler->hasNotPublished(SpecialistApprovedEvent::EVENT_NAME)) {
            throw new \Exception('The Project Management Team has not been notified the Specialist has been approved');
        }
    }

    /**
     * @Then The Project Management team should be notified that the Specialist has been discarded
     */
    public function theProjectManagementTeamShouldBeNotifiedThatTheSpecialistHasBeenDiscarded()
    {
        $eventHandler = $this->app->getContainer()->get('redis_handler');
        if ($eventHandler->hasNotPublished(SpecialistDiscardedEvent::EVENT_NAME)) {
            throw new \Exception('The Project Management Team has not been notified the Specialist has been discarded');
        }
    }

    /**
     * @Then The Project Management Team should be notified that the Consultation has been scheduled
     */
    public function theProjectManagementTeamShouldBeNotifiedThatTheConsultationHasBeenScheduled()
    {
        $eventHandler = $this->app->getContainer()->get('redis_handler');
        if ($eventHandler->hasNotPublished(ConsultationScheduledEvent::EVENT_NAME)) {
            throw new \Exception(
                'The Project Management Team has not been notified the Consultation has been scheduled'
            );
        }
    }

    /**
     * @Then The Project Management Team should be notified that the Project has started
     */
    public function theProjectManagementTeamShouldBeNotifiedThatTheProjectHasStarted()
    {
        $eventHandler = $this->app->getContainer()->get('redis_handler');
        if ($eventHandler->hasNotPublished(ProjectStartedEvent::EVENT_NAME)) {
            throw new \Exception(
                'The Project Management Team has not been notified the Project has started'
            );
        }
    }

    /**
     * @Then The Consultation should be marked as confirmed
     */
    public function theConsultationShouldBeMarkedAsConfirmed()
    {
        //Read side...
    }

    /**
     * @Then The Consultation should be marked as discarded
     */
    public function theConsultationShouldBeMarkedAsDiscarded()
    {
        //Read side..
    }
}
