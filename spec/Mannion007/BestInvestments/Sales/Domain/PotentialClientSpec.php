<?php

namespace spec\Mannion007\BestInvestments\Sales\Domain;

use Mannion007\BestInvestments\Sales\Domain\ContactDetails;
use Mannion007\BestInvestments\Sales\Domain\Money;
use Mannion007\BestInvestments\Sales\Domain\PotentialClient;
use PhpSpec\ObjectBehavior;

/**
 * Class PotentialClientSpec
 * @package spec\Mannion007\BestInvestments\Sales\Domain
 * @mixin PotentialClient
 */
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
        $this->signUp(new Money(100))->shouldBeAnInstanceOf('Mannion007\BestInvestments\Sales\Domain\Client');
    }
}
