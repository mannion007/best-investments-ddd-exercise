<?php

namespace Mannion007\BestInvestments\Domain\Sales;

class Package
{
    private $reference;
    private $clientId;
    private $nominalHours;
    private $startDate;
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
        /** Raise a package_purchased event */
    }

    private function isDueToStart() : bool
    {
        return $this->startDate <= new \DateTime();
    }
}
