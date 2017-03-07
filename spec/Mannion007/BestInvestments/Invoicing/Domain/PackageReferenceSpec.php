<?php

namespace spec\Mannion007\BestInvestments\Invoicing\Domain;

use Mannion007\BestInvestments\Invoicing\Domain\PackageReference;
use PhpSpec\ObjectBehavior;

/**
 * Class PackageReferenceSpec
 * @package spec\Mannion007\BestInvestments\Invoicing\PackageReference
 * @mixin PackageReference
 */
class PackageReferenceSpec extends ObjectBehavior
{
    function it_initializes_from_existing()
    {
        $this->beConstructedThrough('fromExisting', ['test-2015-01-12']);
        $this->__toString()->shouldBe('test-2015-01-12');

        $this->getStartDate()->shouldReturnAnInstanceOf('\DateTime');
        $this->getStartDate()->format('Y')->shouldBe('2015');
        $this->getStartDate()->format('m')->shouldBe('01');

        $this->getMonths()->shouldReturnAnInstanceOf('Mannion007\BestInvestments\Invoicing\Domain\PackageDuration');
        $this->getMonths()->__toString()->shouldBe('12');
    }
}
