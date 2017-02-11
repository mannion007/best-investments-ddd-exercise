<?php

namespace spec\Mannion007\BestInvestments\Invoicing\Domain;

use Mannion007\BestInvestments\Invoicing\Domain\Package;
use PhpSpec\ObjectBehavior;

/**
 * Class PackageDurationSpec
 * @package spec\Mannion007\BestInvestments\Invoicing\Domain
 * @mixin Package
 */
class PackageDurationSpec extends ObjectBehavior
{
    function it_does_not_initialise_with_five_months()
    {
        $this->beConstructedWith(5);
        $this->shouldThrow(new \Exception('Invalid number of months for Package Duration'))
            ->duringInstantiation();
    }

    function it_initialises_with_six_months()
    {
        $this->beConstructedWith(6);
        $this->shouldNotThrow(new \Exception('Invalid number of months for Package Duration'))
            ->duringInstantiation();
    }

    function it_initialises_with_twelve_months()
    {
        $this->beConstructedWith(12);
        $this->shouldNotThrow(new \Exception('Invalid number of months for Package Duration'))
            ->duringInstantiation();
    }
}
