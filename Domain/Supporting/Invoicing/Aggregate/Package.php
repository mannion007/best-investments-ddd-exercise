<?php

class Package
{
    /** @var PackageReference */
    private $reference;

    /** @var ClientId */
    private $clientId;

    /** @var TimeIncrement */
    private $nominalHours;

    /** @var TimeIncrement */
    private $transferredInTime;

    /** @var TimeIncrement */
    private $transferredOutTime;

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
    }

    public function attach(Consultation $consultation)
    {
        if ($this->status->isNot(PackageStatus::ACTIVE)) {
            throw new Exception('Cannot attach a consultation to a Package that is not Active');
        }
        if ($this->getUsedTime()->add($consultation->getTime())->isMoreThan($this->getAvailableTime())) {
            throw new Exception('Package does not have enough hours remaining');
        }
        if ($this->clientId->isNot($consultation->getClientId())) {
            throw new Exception('Cannot attach a Consultation for another Client');
        }
        $this->attachedConsultations[] = $consultation;
    }

    private function getUsedTime() : TimeIncrement
    {
        $usedHours = new TimeIncrement(0);
        foreach ($this->attachedConsultations as $attachedConsultation) {
            $usedHours = $usedHours->add($attachedConsultation->getTime());
        }
        return $usedHours;
    }

    private function getAvailableTime() : TimeIncrement
    {
        return $this->nominalHours->add($this->transferredInTime)->minus($this->transferredOutTime);
    }

    private function transferInTime(TimeIncrement $timeToTransferIn)
    {
        if ($this->status->is(PackageStatus::EXPIRED)) {
            throw new DomainException('Cannot transfer time into an Expired Package');
        }
        $this->transferredInTime = $this->transferredInTime->add($timeToTransferIn);
    }

    private function transferOutTime(TimeIncrement $timeToTransferOut)
    {
        if ($this->status->isNot(PackageStatus::EXPIRED)) {
            throw new DomainException('Cannot transfer time out of a Package that has not yet Expired');
        }
        if ($timeToTransferOut->isMoreThan($this->getAvailableTime())) {
            throw new DomainException('Cannot transfer out more time than the Package has available');
        }
        $this->transferredOutTime = $this->transferredOutTime->add($timeToTransferOut);
    }
}
