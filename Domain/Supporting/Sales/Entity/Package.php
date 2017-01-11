<?php

class Package
{
    private $clientId;
    private $reference;
    private $startDate;
    private $status;

    public function __construct(
        ClientId $clientId,
        string $name,
        DateTime $startDate,
        PackageDuration $durationMonths,
        int $nominalHours
    ) {
        $this->clientId = $clientId;
        $this->startDate = $startDate;
        $this->reference = new PackageReference($name, $startDate, $durationMonths);
        $this->status = $this->isDueToStart() ? PackageStatus::active() : PackageStatus::inactive();
        /** Raise a package_purchased event */
    }

    private function isDueToStart()
    {
        return $this->startDate <= new DateTime();
    }
}
