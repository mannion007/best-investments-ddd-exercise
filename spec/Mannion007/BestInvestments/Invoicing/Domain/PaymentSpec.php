<?php

namespace spec\Mannion007\BestInvestments\Invoicing\Domain;

use Mannion007\BestInvestments\Invoicing\Domain\Payment;
use Mannion007\ValueObjects\Currency;
use PhpSpec\ObjectBehavior;

/**
 * Class MoneySpec
 * @package spec\Mannion007\BestInvestments\Invoicing\Payment
 * @mixin Payment
 */
class PaymentSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(100, Currency::gbp());
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Payment::class);
    }
}
