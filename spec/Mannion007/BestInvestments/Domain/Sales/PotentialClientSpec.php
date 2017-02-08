<?php

namespace spec\Mannion007\BestInvestments\Domain\Sales;

use Mannion007\BestInvestments\Domain\Sales\ContactDetails;
use Mannion007\BestInvestments\Domain\Sales\Money;
use Mannion007\BestInvestments\Domain\Sales\PotentialClient;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PotentialClientSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(
            'Test Potential Client',
            new ContactDetails('07790557557')
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(PotentialClient::class);
    }

    function it_signs_up()
    {
        $this->signUp(new Money(100))->shouldBeAnInstanceOf('Mannion007\BestInvestments\Domain\Sales\Client');
    }
}
