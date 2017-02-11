<?php

namespace spec\Mannion007\BestInvestments\Domain\Prospecting;

use Mannion007\BestInvestments\Prospecting\Domain\Money;
use Mannion007\BestInvestments\Prospecting\Domain\Prospect;
use Mannion007\BestInvestments\Prospecting\Domain\ProspectGivenUpOnEvent;
use Mannion007\BestInvestments\Prospecting\Domain\ProspectId;
use Mannion007\BestInvestments\Prospecting\Domain\ProspectNotInterestedEvent;
use Mannion007\BestInvestments\Prospecting\Domain\ProspectReceivedEvent;
use Mannion007\BestInvestments\Prospecting\Domain\ProspectRegisteredEvent;
use Mannion007\BestInvestments\Prospecting\Domain\ProspectStatus;
use Mannion007\BestInvestments\Event\InMemoryEventPublisher;
use Mannion007\BestInvestments\Event\EventPublisher;
use PhpSpec\ObjectBehavior;

/**
 * Class ProspectSpec
 * @package spec\Mannion007\BestInvestments\Prospecting\Domain
 * @mixin Prospect
 */
class ProspectSpec extends ObjectBehavior
{
    /** @var InMemoryEventPublisher */
    private $handler;

    function let()
    {
        /** Find before suite annotation to improve this */
        $this->handler = new InMemoryEventPublisher();
        EventPublisher::registerPublisher($this->handler);

        $this->beConstructedThrough(
            'receive',
            [ProspectId::fromExisting('prospect-123'), 'Test Prospect', 'This is a test prospect.']
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Prospect::class);
        if ($this->handler->hasNotPublished(ProspectReceivedEvent::EVENT_NAME)) {
            throw new \Exception('An event should have been published when the Prospect was received');
        }
    }

    function it_cannot_be_chased_up_when_it_is_not_in_progress()
    {
        $status = new \ReflectionProperty($this->getWrappedObject(), 'status');
        $status->setAccessible(true);
        $status->setValue($this->getWrappedObject(), ProspectStatus::notInterested());

        $this->shouldThrow(new \Exception('Cannot chase up prospect that is not In Progress'))
            ->during('chaseUp');
    }

    function it_can_be_chased_up()
    {
        $this->chaseUp();
    }

    function it_cannot_register_when_it_is_not_in_progress()
    {
        $status = new \ReflectionProperty($this->getWrappedObject(), 'status');
        $status->setAccessible(true);
        $status->setValue($this->getWrappedObject(), ProspectStatus::notInterested());

        $this->shouldThrow(new \Exception('Cannot register Prospect that is not In Progress'))
            ->during('register', [new Money(100)]);
    }

    function it_registers()
    {
        $this->register(new Money(100));
        if ($this->handler->hasNotPublished(ProspectRegisteredEvent::EVENT_NAME)) {
            throw new \Exception('An event should have been published when the Prospect was Registered');
        }
    }

    function it_cannot_declare_not_interested_when_it_is_not_in_progress()
    {
        $status = new \ReflectionProperty($this->getWrappedObject(), 'status');
        $status->setAccessible(true);
        $status->setValue($this->getWrappedObject(), ProspectStatus::notInterested());

        $this->shouldThrow(new \Exception('Cannot declare not interested for Prospect that is not In Progress'))
            ->during('declareNotInterested');
    }

    function it_declares_not_interested()
    {
        $this->declareNotInterested();
        if ($this->handler->hasNotPublished(ProspectNotInterestedEvent::EVENT_NAME)) {
            throw new \Exception('An event should have been published when the Prospect was Declared Not Interested');
        }
    }

    function it_cannot_give_up_not_interested_when_it_is_not_in_progress()
    {
        $status = new \ReflectionProperty($this->getWrappedObject(), 'status');
        $status->setAccessible(true);
        $status->setValue($this->getWrappedObject(), ProspectStatus::notInterested());

        $this->shouldThrow(new \Exception('Cannot give up on Prospect that is not In Progress'))
            ->during('giveUp');
    }

    function it_gives_up()
    {
        $this->giveUp();
        if ($this->handler->hasNotPublished(ProspectGivenUpOnEvent::EVENT_NAME)) {
            throw new \Exception('An event should have been published when the Prospect was Given Up On');
        }
    }
}