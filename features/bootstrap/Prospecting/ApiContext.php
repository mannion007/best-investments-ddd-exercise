<?php

namespace Mannion007\BestInvestmentsBehat\Prospecting;

use Behat\Behat\Context\Context;
use Pavlakis\Slim\Behat\Context\App;
use Pavlakis\Slim\Behat\Context\KernelAwareContext;
use \GuzzleHttp\Client;

/**
 * Defines application features from the specific context.
 */
class ApiContext implements Context, KernelAwareContext
{
    use App;

    private $prospectId;

    private $guzzle;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->prospectId = 'test-prospect-id';
        $this->guzzle = new Client();
    }

    /**
     * @Given I have received a Prospect
     */
    public function iHaveReceivedAProspect()
    {
        $this->guzzle->put(
            sprintf(
                '%s/prospect/receive/%s',
                $this->app->getContainer()->get('prospecting_base_uri'),
                $this->prospectId
            ),
            [
                'form_params' => [
                    'name' => 'Test Specialist',
                    'notes' => 'This is just a test'
                ]
            ]
        );
    }

    /**
     * @When I chase up the Prospect
     */
    public function iChaseUpTheProspect()
    {
        $this->guzzle->post(
            sprintf('%s/prospect/chase_up', $this->app->getContainer()->get('prospecting_base_uri')),
            ['form_params' => ['prospect_id' => $this->prospectId]]
        );
    }

    /**
     * @When The Prospect registers
     */
    public function theProspectRegisters()
    {
        $this->guzzle->post(
            sprintf('%s/prospect/register', $this->app->getContainer()->get('prospecting_base_uri')),
            ['form_params' => ['prospect_id' => $this->prospectId, 'hourly_rate' => '150']]
        );
        $this->eventShouldHaveBeenPublishedNamed('prospect_registered');
    }

    /**
     * @When I declare the Prospect as not interested
     */
    public function iDeclareTheProspectAsNotInterested()
    {
        $this->guzzle->post(
            sprintf('%s/prospect/declare_not_interested', $this->app->getContainer()->get('prospecting_base_uri')),
            ['form_params' => ['prospect_id' => $this->prospectId]]
        );
        $this->eventShouldHaveBeenPublishedNamed('prospect_not_interested');
    }

    /**
     * @When I give up on the Prospect
     */
    public function iGiveUpOnTheProspect()
    {
        $this->guzzle->post(
            sprintf('%s/prospect/give_up', $this->app->getContainer()->get('prospecting_base_uri')),
            ['form_params' => ['prospect_id' => $this->prospectId]]
        );
        $this->eventShouldHaveBeenPublishedNamed('prospect_given_up_on');
    }

    /**
     * @Then The date and time of the chase up should be recorded
     */
    public function theDateAndTimeOfTheChaseUpShouldBeRecorded()
    {
        $response = $this->guzzle->get(
            sprintf('%s/prospect/%s', $this->app->getContainer()->get('prospecting_base_uri'), $this->prospectId)
        );
        $decodedResponse = json_decode($response->getBody());
        if (empty($decodedResponse->chase_ups)) {
            throw new \Exception('The date and time of the chase up has not been recorded');
        }
    }

    /**
     * @Then The Prospect should be marked as :status
     */
    public function theProspectShouldBeMarkedAs(string $status)
    {
        $this->prospectShouldBe($status);
    }

    private function eventShouldHaveBeenPublishedNamed(string $eventName)
    {
        $eventPublisher = $this->app->getContainer()->get('prospecting_redis_publisher');
        if ($eventPublisher->hasNotPublished($eventName)) {
            throw new \Exception(
                'The event has not been published'
            );
        }
    }

    private function prospectShouldBe(string $status)
    {
        $response = $this->guzzle->get(
            sprintf('%s/prospect/%s', $this->app->getContainer()->get('prospecting_base_uri'), $this->prospectId)
        );
        $decodedResponse = json_decode($response->getBody());
        if ($status !== $decodedResponse->status->status) {
            throw new \Exception(sprintf('The Prospect has not been marked as %s', $status));
        }
    }
}
