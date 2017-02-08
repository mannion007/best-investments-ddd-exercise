<?php

namespace spec\Mannion007\BestInvestments\Domain\Sales;

use Mannion007\BestInvestments\Domain\Sales\ClientId;
use Mannion007\BestInvestments\Domain\Sales\Package;
use Mannion007\BestInvestments\Domain\Sales\PackageDuration;
use Mannion007\BestInvestments\Domain\Sales\PackagePurchasedEvent;
use Mannion007\BestInvestments\Event\EventPublisher;
use Mannion007\BestInvestments\Event\InMemoryHandler;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PackageSpec extends ObjectBehavior
{
    /** @var InMemoryHandler */
    private $handler;

    function let()
    {
        /** Find before suite annotation to improve this */
        $this->handler = new InMemoryHandler();
        EventPublisher::registerHandler($this->handler);

        $this->beConstructedWith(
            ClientId::fromExisting('test-client-id'),
            'Test Package',
            new \DateTime(),
            PackageDuration::sixMonths(),
            100
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Package::class);
        if ($this->handler->hasNotPublished(PackagePurchasedEvent::EVENT_NAME)) {
            throw new \Exception('An event should have been published when the Package was purchased');
        }
    }
}
