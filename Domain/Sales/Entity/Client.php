<?php

class Client
{
    private $packages = [];

    private function __construct()
    {
    }

    public function purchasePackage(
        string $name,
        DateTime $startDate,
        PackageDurationMonths $durationMonths,
        int $nominalHours
    ) {
        $this->packages[] = Package::purchase($name, $startDate, $durationMonths, $nominalHours);
        /** Raise a package purchased event */
    }
}