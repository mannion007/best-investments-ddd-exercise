<?php

namespace spec\Mannion007\BestInvestments\Sales\Domain;

use Mannion007\BestInvestments\Sales\Domain\ContactDetails;
use Mannion007\BestInvestments\Sales\Domain\Money;
use Mannion007\BestInvestments\Sales\Domain\PayAsYouGoRate;
use Mannion007\BestInvestments\Sales\Domain\PotentialClient;
use Mannion007\ValueObjects\Currency;
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
        $this->signUp(new PayAsYouGoRate(100, Currency::gbp()))
            ->shouldBeAnInstanceOf('Mannion007\BestInvestments\Sales\Domain\Client');
    }
}
