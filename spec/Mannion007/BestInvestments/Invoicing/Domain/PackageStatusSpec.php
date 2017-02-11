<?php

namespace spec\Mannion007\BestInvestments\Domain\Invoicing;

use Mannion007\BestInvestments\Invoicing\Domain\PackageDuration;
use Mannion007\BestInvestments\Invoicing\Domain\PackageStatus;
use PhpSpec\ObjectBehavior;

/**
 * Class PackageStatusSpec
 * @package spec\Mannion007\BestInvestments\Invoicing\Domain
 * @mixin PackageStatus
 */
class PackageStatusSpec extends ObjectBehavior
{
    function it_is_inactive_when_the_start_date_is_in_the_future()
    {
        $this->beConstructedThrough(
            'determineFrom',
            [(new \DateTime())->modify('+1 day'), PackageDuration::sixMonths()]
        );
        $this->is(PackageStatus::INACTIVE)->shouldReturn(true);
    }

    function it_is_active_when_the_start_date_is_in_the_past()
    {
        $this->beConstructedThrough(
            'determineFrom',
            [(new \DateTime())->modify('-1 day'), PackageDuration::sixMonths()]
        );
        $this->is(PackageStatus::ACTIVE)->shouldReturn(true);
    }

    function it_is_expired_when_the_package_duration_has_passed_since_the_start_date()
    {
        $this->beConstructedThrough(
            'determineFrom',
            [(new \DateTime())->modify('-1 year'), PackageDuration::sixMonths()]
        );
        $this->is(PackageStatus::EXPIRED)->shouldReturn(true);
    }
}
