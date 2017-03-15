<?php

namespace Mannion007\BestInvestments\Invoicing\Domain;

class Package
{
    /** @var PackageReference */
    private $reference;

    /** @var ClientId */
    private $clientId;

    /** @var int */
    private $nominalHours;

    /** @var TimeIncrement */
    private $availableHours;

    /** @var TransferTime */
    private $transferredOutHours;

    /** @var TimeIncrement[] */
    private $attachedConsultations = [];

    /** @var PackageStatus */
    private $status;

    public function __construct(PackageReference $reference, ClientId $clientId, int $nominalHours)
    {
        $this->reference = $reference;
        $this->clientId = $clientId;
        $this->nominalHours = $nominalHours;
        $this->availableHours = new TimeIncrement($nominalHours * 60);
        $this->transferredOutHours = new TimeIncrement(0);
        $this->status = PackageStatus::determineFrom(
            $this->reference->getStartDate(),
            $this->reference->getLength()
        );
    }

    public function getReference(): PackageReference
    {
        return $this->reference;
    }

    public function attach(OutstandingConsultation $outstandingConsultation)
    {
        if ($this->status->isNot(PackageStatus::active())) {
            throw new \InvalidArgumentException(
                'Cannot attach a Consultation to a Package that is not active'
            );
        }
        if ($outstandingConsultation->getClientId()->isNot($this->clientId)) {
            throw new \InvalidArgumentException(
                'Cannot attach a Consultation which belongs to a different Client'
            );
        }
        if ($outstandingConsultation->getDuration()->isMoreThan($this->getRemainingHours())) {
            throw new \InvalidArgumentException(
                'Cannot attach a Consultation with a duration which exceeds the available hours of a Package'
            );
        }
        $this->attachedConsultations[(string)$outstandingConsultation->getConsultationId()]
            = $outstandingConsultation->getDuration();
    }

    private function getRemainingHours(): TimeIncrement
    {
        $usedHours = new TimeIncrement(0);
        foreach ($this->attachedConsultations as $duration) {
            $usedHours = $usedHours->add($duration);
        }
        return $this->availableHours->minus($usedHours)->minus($this->transferredOutHours);
    }

    public function transferOutRemainingHours(): TransferTime
    {
        if ($this->status->isNot(PackageStatus::expired())) {
            throw new \Exception('Cannot transfer out remaining hours from a Package that is not expired');
        }
        $this->transferredOutHours = new TimeIncrement($this->getRemainingHours()->inMinutes());
        return new TransferTime($this->transferredOutHours->inMinutes(), $this->clientId);
    }

    public function transferInExtraHours(TransferTime $extraHours): void
    {
        if ($this->status->isNot(PackageStatus::notYetStarted())) {
            throw new \Exception(
                'Cannot transfer extra hours into a package that has already started'
            );
        }
        if ($extraHours->doesNotBelongTo($this->clientId)) {
            throw new \Exception(
                'Cannot transfer extra hours in from a Package that belongs to a different Client'
            );
        }
        $this->availableHours = $this->availableHours->add($extraHours);
    }
}
