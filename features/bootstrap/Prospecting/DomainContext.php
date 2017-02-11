<?php

namespace Mannion007\BestInvestmentsBehat\Prospecting;

use Behat\Behat\Context\Context;
use Mannion007\BestInvestments\Prospecting\Domain\Money;
use Mannion007\BestInvestments\Prospecting\Domain\Prospect;
use Mannion007\BestInvestments\Prospecting\Domain\ProspectGivenUpOnEvent;
use Mannion007\BestInvestments\Prospecting\Domain\ProspectId;
use Mannion007\BestInvestments\Prospecting\Domain\ProspectNotInterestedEvent;
use Mannion007\BestInvestments\Prospecting\Domain\ProspectRegisteredEvent;
use Mannion007\BestInvestments\Prospecting\Domain\ProspectStatus;
use Mannion007\BestInvestments\Event\EventPublisher;
use Mannion007\BestInvestments\Event\InMemoryHandler;

/**
 * Defines application features from the specific context.
 */
class DomainContext implements Context
{
    /** @var InMemoryHandler */
    private $eventHandler;

    /** @var ProspectId */
    private $prospectId;

    /** @var Prospect */
    private $prospect;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->eventHandler = new InMemoryHandler();
        EventPublisher::registerHandler($this->eventHandler);
        $this->prospectId = ProspectId::fromExisting('test-prospect-id');
    }

    /**
     * @Given I have received a Prospect
     */
    public function iHaveReceivedAProspect()
    {
        $this->prospect = Prospect::receive($this->prospectId, 'Test Prospect', 'Prospect notes');
    }

    /**
     * @When I chase up the Prospect
     */
    public function iChaseUpTheProspect()
    {
        $this->prospect->chaseUp();
    }

    /**
     * @When The Prospect registers
     */
    public function theProspectRegisters()
    {
        $this->prospect->register(new Money(100));
    }

    /**
     * @When I declare the Prospect as not interested
     */
    public function iDeclareTheProspectAsNotInterested()
    {
        $this->prospect->declareNotInterested();
    }

    /**
     * @When I give up on the Prospect
     */
    public function iGiveUpOnTheProspect()
    {
        $this->prospect->giveUp();
    }

    /**
     * @Then The date and time of the chase up should be recorded
     */
    public function theDateAndTimeOfTheChaseUpShouldBeRecorded()
    {
        $reflected = new \ReflectionProperty($this->prospect, 'chaseUps');
        $reflected->setAccessible(true);
        if (empty($reflected->getValue($this->prospect))) {
            throw new\Exception('The chase-up has not been recorded');
        }
    }

    /**
     * @Then The Prospect should be marked as :status
     */
    public function theProspectShouldBeMarkedAs(string $expected)
    {
        switch ($expected) {
            case 'registered':
                $status = ProspectStatus::REGISTERED;
                break;
            case 'in progress':
                $status = ProspectStatus::IN_PROGRESS;
                break;
            case 'not interested':
                $status = ProspectStatus::NOT_INTERESTED;
                break;
            case 'not reachable':
                $status = ProspectStatus::NOT_REACHABLE;
                break;
            default:
                throw new \Exception(sprintf('Unknown status type:', $expected));
        }

        if ($this->getStatus()->isNot($status)) {
            throw new\Exception(
                sprintf('The prospect is not marked as %s', $status)
            );
        }
    }

    /**
     * @Then The Project Management Team should be notified that the Prospect has registered
     */
    public function theProjectManagementTeamShouldBeNotifiedThatTheProspectHasRegistered()
    {
        if ($this->eventHandler->hasNotPublished(ProspectRegisteredEvent::EVENT_NAME)) {
            throw new \Exception('The Project Management Team has not been notified that Prospect has registered');
        }
    }

    /**
     * @Then The Project Management Team should be notified that the Prospect is not interested
     */
    public function theProjectManagementTeamShouldBeNotifiedThatTheProspectIsNotInterested()
    {
        if ($this->eventHandler->hasNotPublished(ProspectNotInterestedEvent::EVENT_NAME)) {
            throw new \Exception('The Project Management Team has not been notified that Prospect is not interested');
        }
    }

    /**
     * @Then The Project Management Team should be notified that the Prospect has been given up on
     */
    public function theProjectManagementTeamShouldBeNotifiedThatTheProspectHasBeenGivenUpOn()
    {
        if ($this->eventHandler->hasNotPublished(ProspectGivenUpOnEvent::EVENT_NAME)) {
            throw new \Exception('The Project Management Team has not been notified that Prospect is not reachable');
        }
    }

    private function getStatus(): ProspectStatus
    {
        $reflected = new \ReflectionProperty($this->prospect, 'status');
        $reflected->setAccessible(true);
        return $reflected->getValue($this->prospect);
    }
}
