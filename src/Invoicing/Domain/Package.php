<?php

namespace Mannion007\BestInvestments\Invoicing\Domain;

class Package
{
    private $reference;
    private $clientId;
    private $nominalHours;
    private $attachedConsultations = [];
    private $transferredInHours;
    private $transferredOutHours;
    private $status;

    public function __construct(
        PackageReference $reference,
        ClientId $clientId,
        TimeIncrement $nominalHours
    ) {
        $this->reference = $reference;
        $this->clientId = $clientId;
        $this->nominalHours = $nominalHours;
        $this->transferredInHours = new TimeIncrement(0);
        $this->transferredOutHours = new TransferTime($this->clientId, 0);
        $this->status = PackageStatus::determineFrom($reference->getStartDate(), $reference->getMonths());
    }

    public function attach(Consultation $consultation)
    {
        if ($this->status->isNot(PackageStatus::ACTIVE)) {
            throw new \Exception('Cannot attach a consultation to a Package that is not Active');
        }
        if ($this->getUsedHours()->add($consultation->getDuration())->isMoreThan($this->getRemainingHours())) {
            throw new \Exception('Package does not have enough hours remaining');
        }
        if ($this->clientId->isNot($consultation->getClientId())) {
            throw new \Exception('Cannot attach a Consultation for another Client');
        }
        $this->attachedConsultations[] = $consultation;
    }

    private function getRemainingHours(): TimeIncrement
    {
        return $this->getAvailableHours()->minus($this->getUsedHours())->minus($this->transferredOutHours->getTime());
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
        return $consultationHours->minus($this->transferredOutHours->getTime());
    }

    public function transferInHours(TransferTime $timeToTransferIn): void
    {
        if ($this->status->is(PackageStatus::ACTIVE)) {
            throw new \Exception('Cannot transfer hours into an Active Package');
        }
        if ($this->status->is(PackageStatus::EXPIRED)) {
            throw new \Exception('Cannot transfer hours into an Expired Package');
        }
        if ($timeToTransferIn->doesNotBelongTo($this->clientId)) {
            throw new \Exception('Cannot transfer hours into an Package that belongs to a different client');
        }
        $this->transferredInHours = $this->transferredInHours->add($timeToTransferIn->getTime());
    }

    public function transferOutHours(): TransferTime
    {
        if ($this->status->isNot(PackageStatus::EXPIRED)) {
            throw new \Exception('Cannot transfer hours out of a Package that has not yet Expired');
        }
        $this->transferredOutHours = new TransferTime($this->clientId, $this->getRemainingHours()->inMinutes());
        return $this->transferredOutHours;
    }
}
