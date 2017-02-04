<?php

namespace Mannion007\BestInvestmentsBehat;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Mannion007\BestInvestments\Application\ProjectService;
use Mannion007\BestInvestments\Domain\ProjectManagement\ProjectDraftedEvent;
use Mannion007\BestInvestments\Domain\ProjectManagement\ProjectReference;
use Mannion007\BestInvestments\Domain\ProjectManagement\ProjectStatus;
use Mannion007\BestInvestments\Event\EventPublisher;
use Mannion007\BestInvestments\Event\InMemoryHandler;
use Mannion007\BestInvestments\Infrastructure\Storage\InMemoryProjectRepositoryAdapter;
use Slim\Container;

/**
 * Defines application features from the specific context.
 */
class ProjectManagementContext implements Context
{
    /** @var ProjectService */
    private $projectService;

    /** @var InMemoryProjectRepositoryAdapter */
    private $projectRepository;

    /** @var InMemoryHandler */
    private $eventHandler;

    /** @var string */
    private $clientId;

    /** @var string */
    private $projectReference;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $container = new Container;
        $container['in_memory_project_repository'] = function () {
            return new InMemoryProjectRepositoryAdapter();
        };
        $container['project_service'] = function ($container) {
            return new ProjectService($container['in_memory_project_repository']);
        };

        $this->projectService = $container->get('project_service');
        $this->projectRepository = $container->get('in_memory_project_repository');
        $this->eventHandler = new InMemoryHandler();
        EventPublisher::registerHandler($this->eventHandler);
    }

    /**
     * @Given I have a Client
     */
    public function iHaveAClient()
    {
        $this->clientId = 'test-client-123';
    }

    /**
     * @Given I have a Draft Project
     */
    public function iHaveADraftProject()
    {
        $this->clientId = 'test-client-123';
        $this->projectReference = $this->projectService->setUpProject($this->clientId, 'Test Project', '2020-05-20');
    }


    /**
     * @When I Set Up a Project for the Client with the name :name and the deadline :deadline
     */
    public function iSetUpAProjectForTheClientWithTheNameAndTheDeadline($name, $deadline)
    {
        $this->projectReference = $this->projectService->setUpProject($this->clientId, $name, $deadline);
    }

    /**
     * @Then I should have a Draft of a Project
     */
    public function iShouldHaveADraftOfAProject()
    {
        $project = $this->projectRepository->getByReference(
            ProjectReference::fromExisting($this->projectReference)
        );
        if ($project->isNot(ProjectStatus::DRAFT)) {
            throw new \Exception('The project is not drafted');
        }
    }

    /**
     * @Then I should get a Project Reference
     */
    public function iShouldGetAProjectReference()
    {
        if (is_null($this->projectReference)) {
            throw new \Exception('I did not get a Project Reference');
        }
    }

    /**
     * @Then A Senior Project Manager should be notified
     */
    public function aSeniorProjectManagerShouldBeNotified()
    {
        if (!$this->eventHandler->hasPublished(ProjectDraftedEvent::EVENT_NAME)) {
            throw new \Exception('A Senior Project Manager has not been notified');
        }
    }

    /**
     * @When I assign a Project Manager to the Project
     */
    public function iAssignAProjectManagerToTheProject()
    {
        $this->projectService->startProject($this->projectReference, 'test-manager-123');
    }

    /**
     * @Then The Project should start
     */
    public function theProjectShouldStart()
    {
        $project = $this->projectRepository->getByReference(
            ProjectReference::fromExisting($this->projectReference)
        );
        if ($project->isNot(ProjectStatus::ACTIVE)) {
            throw new \Exception('The Project is not active');
        }
    }

    /**
     * @Then Specialists can be added to the Project
     */
    public function specialistsCanBeAddedToTheProject()
    {
        $this->projectService->addSpecialistToProject($this->projectReference, 'test-specialist-id');
    }
}
