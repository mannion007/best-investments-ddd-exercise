<?php

namespace spec\Mannion007\BestInvestments\Invoicing\Domain;

use Mannion007\BestInvestments\Invoicing\Domain\Package;
use Mannion007\BestInvestments\Invoicing\Domain\Consultation;
use Mannion007\BestInvestments\Invoicing\Domain\ConsultationId;
use Mannion007\BestInvestments\Invoicing\Domain\PackageDuration;
use Mannion007\BestInvestments\Invoicing\Domain\PackageReference;
use Mannion007\BestInvestments\Invoicing\Domain\ClientId;
use Mannion007\BestInvestments\Invoicing\Domain\PackageStatus;
use Mannion007\BestInvestments\Invoicing\Domain\TimeIncrement;
use Mannion007\BestInvestments\Invoicing\Domain\TransferTime;
use PhpSpec\ObjectBehavior;

/**
 * Class PackageSpec
 * @package spec\Mannion007\BestInvestments\Invoicing\Domain
 * @mixin Package
 */
class PackageSpec extends ObjectBehavior
{
    function let()
    {
        $yesterday = (new \DateTime())->modify('-1 day');
        $reference = new PackageReference('test-ref', $yesterday, PackageDuration::sixMonths());
        $clientId = ClientId::fromExisting('client1');
        $nominalHours = new TimeIncrement(60);
        $this->beConstructedWith($reference, $clientId, $nominalHours);
    }

    function it_does_not_attach_a_consultation_when_it_is_not_active(Consultation $consultation)
    {
        $this->makeInactive();
        $this->shouldThrow(new \Exception('Cannot attach a consultation to a Package that is not Active'))
            ->during('attach', [$consultation]);
    }

    function it_does_not_attach_a_consultation_when_it_has_insufficient_remaining_hours()
    {
        $consultationId = ConsultationId::fromExisting(123);
        $clientId = ClientId::fromExisting('client1');
        $duration = new TimeIncrement(120);
        $consultation = Consultation::schedule($consultationId, $clientId, $duration);
        $this->shouldThrow(new \Exception('Package does not have enough hours remaining'))
            ->during('attach', [$consultation]);
    }

    public function it_does_not_attach_a_consultation_for_another_client()
    {
        $consultationId = ConsultationId::fromExisting(123);
        $clientId = ClientId::fromExisting('client2');
        $duration = new TimeIncrement(30);
        $consultation = Consultation::schedule($consultationId, $clientId, $duration);
        $this->shouldThrow(new \Exception('Cannot attach a Consultation for another Client'))
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

    function it_does_not_transfer_in_hours_when_it_is_active()
    {
        $timeToTransferIn = new TransferTime(ClientId::fromExisting('client1'), 30);
        $this->shouldThrow(new \Exception('Cannot transfer hours into an Active Package'))
            ->during('transferInHours', [$timeToTransferIn]);
    }

    function it_does_not_transfer_in_hours_when_it_is_expired()
    {
        $this->makeExpired();
        $timeToTransferIn = new TransferTime(ClientId::fromExisting('client1'), 30);
        $this->shouldThrow(new \Exception('Cannot transfer hours into an Expired Package'))
            ->during('transferInHours', [$timeToTransferIn]);
    }

    function it_does_not_transfer_in_hours_from_a_different_client()
    {
        $this->makeInactive();
        $timeToTransferIn = new TransferTime(ClientId::fromExisting('client2'), 30);
        $this->shouldThrow(new \Exception('Cannot transfer hours into an Package that belongs to a different client'))
            ->during('transferInHours', [$timeToTransferIn]);
    }

    function it_transfers_in_hours()
    {
        $this->makeInactive();
        $timeToTransferIn = new TransferTime(ClientId::fromExisting('client1'), 30);
        $this->transferInHours($timeToTransferIn);
    }

    function it_does_not_transfer_out_hours_when_it_is_not_expired()
    {
        $this->shouldThrow(new \Exception('Cannot transfer hours out of a Package that has not yet Expired'))
            ->during('transferOutHours');
    }

    function it_transfers_out_hours()
    {
        $this->makeExpired();
        $this->transferOutHours();
    }

    private function makeInactive()
    {
        $started = PackageStatus::determineFrom(new \DateTime('+1 day'), PackageDuration::sixMonths());
        $status = new \ReflectionProperty($this->getWrappedObject(), 'status');
        $status->setAccessible(true);
        $status->setValue($this->getWrappedObject(), $started);
    }

    private function makeExpired()
    {
        $expired = PackageStatus::determineFrom(new \DateTime('-5 years'), PackageDuration::sixMonths());
        $status = new \ReflectionProperty($this->getWrappedObject(), 'status');
        $status->setAccessible(true);
        $status->setValue($this->getWrappedObject(), $expired);
    }
}
