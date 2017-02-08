<?php

namespace spec\Mannion007\BestInvestments\Domain\ProjectManagement;

use Mannion007\BestInvestments\Domain\ProjectManagement\Money;
use Mannion007\BestInvestments\Domain\ProjectManagement\Specialist;
use Mannion007\BestInvestments\Domain\ProjectManagement\SpecialistId;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class SpecialistSpec
 * @package spec\Mannion007\BestInvestments\Domain\ProjectManagement
 * @mixin Specialist
 */
class SpecialistSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Specialist::class);
    }

    function it_registers()
    {
        Specialist::register(
            SpecialistId::fromExisting('test-specialist-id'),
            'Test Specialist',
            new Money(100)
        );
    }
}
