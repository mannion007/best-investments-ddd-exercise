<?php

namespace spec\Mannion007\BestInvestments\Domain\Invoicing;

use Mannion007\BestInvestments\Domain\Invoicing\Package;
use Mannion007\BestInvestments\Domain\Invoicing\Consultation;
use Mannion007\BestInvestments\Domain\Invoicing\ConsultationId;
use Mannion007\BestInvestments\Domain\Invoicing\PackageDuration;
use Mannion007\BestInvestments\Domain\Invoicing\PackageReference;
use Mannion007\BestInvestments\Domain\Invoicing\ClientId;
use Mannion007\BestInvestments\Domain\Invoicing\TimeIncrement;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class PackageSpec
 * @package spec\Mannion007\BestInvestments\Domain\Invoicing
 * @mixin Package
 */
class PackageSpec extends ObjectBehavior
{
    function let()
    {
        $today = new \DateTimeImmutable();
        $yesterday = $today->sub(new \DateInterval('P1D'));
        $reference = new PackageReference('test-ref', $yesterday, PackageDuration::sixMonths());
        $clientId = ClientId::fromExisting('client1');
        $nominalHours = new TimeIncrement(60);
        $this->beConstructedWith($reference, $clientId, $nominalHours);
    }

    function it_does_not_attach_a_consultation_when_it_is_not_active(
        ClientId $clientId,
        TimeIncrement $nominalHours,
        Consultation $consultation
    ) {
        $today = new \DateTimeImmutable();
        $tomorrow = $today->add(new \DateInterval('P1D'));
        $reference = new PackageReference('test-ref', $tomorrow, PackageDuration::sixMonths());
        $this->beConstructedWith($reference, $clientId, $nominalHours);
        $this->shouldThrow(new \DomainException('Cannot attach a consultation to a Package that is not Active'))
            ->during('attach', [$consultation]);
    }

    function it_does_not_attach_a_consultation_when_it_has_insufficient_remaining_hours()
    {
        $consultationId = ConsultationId::fromExisting(123);
        $clientId = ClientId::fromExisting('client1');
        $duration = new TimeIncrement(120);
        $consultation = Consultation::schedule($consultationId, $clientId, $duration);
        $this->shouldThrow(new \DomainException('Package does not have enough hours remaining'))
            ->during('attach', [$consultation]);
    }

    public function it_does_not_attach_a_consultation_for_another_client()
    {
        $consultationId = ConsultationId::fromExisting(123);
        $clientId = ClientId::fromExisting('client2');
        $duration = new TimeIncrement(30);
        $consultation = Consultation::schedule($consultationId, $clientId, $duration);
        $this->shouldThrow(new \DomainException('Cannot attach a Consultation for another Client'))
            ->during('attach', [$consultation]);
    }

    function it_attaches_a_consultation()
    {
        $consultationId = ConsultationId::fromExisting(123);
        $clientId = ClientId::fromExisting('client1');
        $duration = new TimeIncrement(30);
        $consultation = Consultation::schedule($consultationId, $clientId, $duration);
        $this->attach($consultation);
    }
}
