<?php

namespace Mannion007\BestInvestments\Domain\Sales;

use Mannion007\BestInvestments\Event\EventPublisher;

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
        \DateTime $startDate,
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
                date_format('c', $this->startDate),
                (string)$this->nominalHours
            )
        );
    }

    private function isDueToStart(): bool
    {
        return $this->startDate <= new \DateTime();
    }
}
