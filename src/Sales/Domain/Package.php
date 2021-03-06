<?php

namespace Mannion007\BestInvestments\Sales\Domain;

use Mannion007\BestInvestments\EventPublisher\EventPublisher;

class Package
{
    private $reference;
    private $clientId;
    private $startDate;
    private $nominalHours;
    private $status;

    public function __construct(
        ClientId $clientId,
        string $name,
        \DateTimeInterface $startDate,
        PackageDuration $durationMonths,
        int $nominalHours
    ) {
        $this->reference = new PackageReference($name, $startDate, $durationMonths);
        $this->clientId = $clientId;
        $this->startDate = $startDate;
        $this->nominalHours = $nominalHours;
        $this->status = $this->isDueToStart() ? PackageStatus::active() : PackageStatus::inactive();

        EventPublisher::publish(
            new PackagePurchasedEvent(
                (string)$this->reference,
                (string)$this->clientId,
                date_format($this->startDate, 'c'),
                (string)$this->nominalHours
            )
        );
    }

    private function isDueToStart(): bool
    {
        return $this->startDate <= new \DateTime();
    }
}
