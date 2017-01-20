<?php

namespace spec\Mannion007\BestInvestments\Domain\Invoicing;

use Mannion007\BestInvestments\Domain\Invoicing\TimeIncrement;
use PhpSpec\ObjectBehavior;

/**
 * Class TimeIncrementSpec
 * @package spec\Mannion007\BestInvestments\Domain\Invoicing
 * @mixin TimeIncrement
 */
class TimeIncrementSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(1);
    }
    function it_does_not_initialise_with_negative_minutes()
    {
        $this->beConstructedWith(-1);
        $this->shouldThrow(new \DomainException('A Time Increment must have a positive number of minutes'))
            ->duringInstantiation();
    }

    function it_initialises_with_zero_minutes()
    {
        $this->beConstructedWith(0);
        $this->shouldNotThrow(new \DomainException('A Time Increment must have a positive number of minutes'))
            ->duringInstantiation();
    }

    function it_initialises_with_positive_minutes()
    {
        $this->shouldNotThrow(new \DomainException('A Time Increment must have a positive number of minutes'))
            ->duringInstantiation();
    }

    function it_rounds_to_quarter_hours()
    {
        $this->inMinutes()->shouldBeEqualTo(15);
        $this->add(new TimeIncrement(1))->inMinutes()->shouldBeEqualTo(30);
    }

    function it_adds()
    {
        $this->add(new TimeIncrement(1))->inMinutes()->shouldBeEqualTo(30);
    }

    function it_does_not_minus_more_minutes_than_it_has()
    {
        $this->shouldThrow(new \DomainException('Cannot minus more time than the time increment has'))
        ->during('minus', [new TimeIncrement(45)]);
    }

    function it_minuses()
    {
        $this->beConstructedWith(30);
        $this->minus(new TimeIncrement(10))->inMinutes()->shouldBeEqualTo(15);
    }
}
