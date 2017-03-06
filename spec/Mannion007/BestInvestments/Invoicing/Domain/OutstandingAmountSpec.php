<?php

namespace spec\Mannion007\BestInvestments\Invoicing\Domain;

use Mannion007\BestInvestments\Invoicing\Domain\OutstandingAmount;
use Mannion007\ValueObjects\Currency;
use PhpSpec\ObjectBehavior;

/**
 * Class OutstandingAmountSpec
 * @package spec\Mannion007\BestInvestments\Invoicing\OutstandingAmount
 * @mixin OutstandingAmount
 */
class OutstandingAmountSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(100, Currency::gbp());
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(OutstandingAmount::class);
    }
}
