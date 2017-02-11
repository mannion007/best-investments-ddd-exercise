<?php

namespace spec\Mannion007\BestInvestments\Sales\Domain;

use Mannion007\BestInvestments\Sales\Domain\ClientId;
use Mannion007\BestInvestments\Sales\Domain\Package;
use Mannion007\BestInvestments\Sales\Domain\PackageDuration;
use Mannion007\BestInvestments\Sales\Domain\PackagePurchasedEvent;
use Mannion007\BestInvestments\Event\EventPublisher;
use Mannion007\BestInvestments\Event\InMemoryHandler;
use PhpSpec\ObjectBehavior;

/**
 * Class PackageSpec
 * @package spec\Mannion007\BestInvestments\Sales\Domain
 * @mixin Package
 */
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
