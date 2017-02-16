<?php

namespace spec\Mannion007\BestInvestments\Sales\Domain;

use Mannion007\BestInvestments\Sales\Domain\ClientId;
use Mannion007\BestInvestments\Sales\Domain\Client;
use Mannion007\BestInvestments\Sales\Domain\ClientSignedUpEvent;
use Mannion007\BestInvestments\Sales\Domain\ClientStatus;
use Mannion007\BestInvestments\Sales\Domain\ContactDetails;
use Mannion007\BestInvestments\Sales\Domain\Money;
use Mannion007\BestInvestments\Sales\Domain\OperationsResumedEvent;
use Mannion007\BestInvestments\Sales\Domain\PackageDuration;
use Mannion007\BestInvestments\Sales\Domain\ServiceSuspendedEvent;
use Mannion007\BestInvestments\Sales\Infrastructure\EventPublisher\InMemoryEventPublisher;
use Mannion007\BestInvestments\EventPublisher\EventPublisher;
use PhpSpec\ObjectBehavior;

/**
 * Class ClientSpec
 * @package spec\Mannion007\BestInvestments\Sales\Domain
 * @mixin Client
 */
class ClientSpec extends ObjectBehavior
{
    /** @var InMemoryEventPublisher */
    private $publisher;

    function let()
    {
        $this->publisher = new InMemoryEventPublisher();
        EventPublisher::registerPublisher($this->publisher);

        $this->beConstructedWith(
            ClientId::fromExisting('test-client-id'),
            'Test Client',
            ContactDetails::fromExisting('07790567765'),
            new Money(100)
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Client::class);
        if ($this->publisher->hasNotPublished(ClientSignedUpEvent::EVENT_NAME)) {
            throw new \Exception('An event should have been published when there was a new Client');
        }
    }

    function it_purchases_packages()
    {
        $this->purchasePackage('Test Purchased Package', new \DateTime('+1 week'), PackageDuration::sixMonths(), 25);
    }

    function it_does_not_suspend_service_when_its_service_is_already_suspended()
    {
        $this->makeSuspended();
        $this->shouldThrow(new \Exception('Cannot suspend the Service of a Client when it is already suspended'))
            ->during('suspendService');
    }

    function it_suspends_service()
    {
        $this->suspendService();
        if ($this->publisher->hasNotPublished(ServiceSuspendedEvent::EVENT_NAME)) {
            throw new \Exception('An event should have been published when the Client\'s service was suspended');
        }
    }

    function it_does_not_resume_operations_when_it_is_not_suspended()
    {
        $this->shouldThrow(new \Exception('Cannot resume operations of a Client that is not suspended'))
            ->during('resumeOperations');
    }

    function it_resumes_operations()
    {
        $this->makeSuspended();
        $this->resumeOperations();
        if ($this->publisher->hasNotPublished(OperationsResumedEvent::EVENT_NAME)) {
            throw new \Exception('An event should have been published when operations were resumed');
        }
    }

    private function makeSuspended()
    {
        $reflected = new \ReflectionProperty($this->getWrappedObject(), 'status');
        $reflected->setAccessible(true);
        $reflected->setValue($this->getWrappedObject(), ClientStatus::suspended());
    }
}
