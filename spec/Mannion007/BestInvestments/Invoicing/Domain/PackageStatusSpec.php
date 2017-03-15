<?php

namespace spec\Mannion007\BestInvestments\Invoicing\Domain;

use Mannion007\BestInvestments\Invoicing\Domain\PackageLength;
use Mannion007\BestInvestments\Invoicing\Domain\PackageStatus;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PackageStatusSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(PackageStatus::class);
    }

    function it_is_not_yet_started_when_start_date_is_in_the_future()
    {
        $this->beConstructedThrough(
            'determineFrom',
            [new \DateTime('+3 months'), PackageLength::sixMonths()]
        );
        $this->is(PackageStatus::notYetStarted())->shouldBe(true);
    }

    function it_is_active_when_expiry_is_in_the_future()
    {
        $this->beConstructedThrough(
            'determineFrom',
            [new \DateTime('-7 months'), PackageLength::twelveMonths()]
        );
        $this->is(PackageStatus::expired())->shouldBe(false);
    }

    function it_is_expired_when_expiry_is_in_the_past()
    {
        $this->beConstructedThrough(
            'determineFrom',
            [new \DateTime('-7 months'), PackageLength::sixMonths()]
        );
        $this->is(PackageStatus::expired())->shouldBe(true);
    }

}
