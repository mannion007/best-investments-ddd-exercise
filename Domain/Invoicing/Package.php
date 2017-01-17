<?php

namespace Mannion007\BestInvestments\Domain\Invoicing;

class Package
{
    /** @var PackageReference */
    private $reference;

    /** @var ClientId */
    private $clientId;

    /** @var TimeIncrement */
    private $nominalHours;

    /** @var TimeIncrement */
    private $transferredInHours;

    /** @var TimeIncrement */
    private $transferredOutHours;

    private $status;

    /** @var Consultation[] */
    private $attachedConsultations;

    public function __construct(
        PackageReference $reference,
        ClientId $clientId,
        TimeIncrement $nominalHours
    ) {
        $this->reference = $reference;
        $this->clientId = $clientId;
        $this->nominalHours = $nominalHours;
        $this->status = PackageStatus::determineFrom($reference->getStartDate(), $reference->getMonths());
        $this->transferredInHours = new TimeIncrement(0);
        $this->transferredOutHours = new TimeIncrement(0);
    }

    public function attach(Consultation $consultation)
    {
        if ($this->status->isNot(PackageStatus::ACTIVE)) {
            throw new \DomainException('Cannot attach a consultation to a Package that is not Active');
        }
        if ($this->getUsedHours()->add($consultation->getDuration())->isMoreThan($this->getRemainingHours())) {
            throw new \DomainException('Package does not have enough hours remaining');
        }
        if ($this->clientId->isNot($consultation->getClientId())) {
            throw new \DomainException('Cannot attach a Consultation for another Client');
        }
        $this->attachedConsultations[] = $consultation;
    }

    private function getRemainingHours(): TimeIncrement
    {
        return $this->getAvailableHours()->minus($this->getUsedHours())->minus($this->transferOutHours());
    }

    private function getAvailableHours(): TimeIncrement
    {
        return $this->nominalHours->add($this->transferredInHours);
    }

    private function getUsedHours(): TimeIncrement
    {
        $consultationHours = new TimeIncrement(0);
        foreach ($this->attachedConsultations as $attachedConsultation) {
            $consultationHours = $consultationHours->add($attachedConsultation->getDuration());
        }
        return $consultationHours->minus($this->transferredOutHours);
    }


    public function transferInHours(TimeIncrement $timeToTransferIn)
    {
        if ($this->status->is(PackageStatus::EXPIRED)) {
            throw new \DomainException('Cannot transfer hours into an Expired Package');
        }
        $this->transferredInHours = $this->transferredInHours->add($timeToTransferIn);
    }

    public function transferOutHours(): TimeIncrement
    {
        if ($this->status->isNot(PackageStatus::EXPIRED)) {
            throw new \DomainException('Cannot transfer hours out of a Package that has not yet Expired');
        }
        /** No guard for 0 available time, that's probably not exceptional to transfer out no time... */
        $this->transferredOutHours = $this->getRemainingHours();
        return $this->transferredOutHours;
    }
}
