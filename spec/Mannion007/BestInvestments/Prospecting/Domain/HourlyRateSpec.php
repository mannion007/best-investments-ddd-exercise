<?php

namespace spec\Mannion007\BestInvestments\Prospecting\Domain;

use Mannion007\BestInvestments\Prospecting\Domain\HourlyRate;
use Mannion007\ValueObjects\Currency;
use PhpSpec\ObjectBehavior;

/**
 * Class HourlyRateSpec
 * @package spec\Mannion007\BestInvestments\Prospecting\HourlyRate
 * @mixin HourlyRate
 */
class HourlyRateSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(100, Currency::gbp());
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(HourlyRate::class);
    }
}
