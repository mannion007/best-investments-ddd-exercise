<?php

class Package
{
    private $name;
    private $startDate;
    private $durationMonths;
    private $nominalHours;
    private $availableHours;

    private $status;

    /**
     * Takes a start date because a package can start in the future...
     * @param string $name
     * @param DateTime $startDate
     * @param PackageDurationMonths $durationMonths
     * @param int $nominalHours
     */
    private function __construct(
        string $name,
        DateTime $startDate,
        PackageDurationMonths $durationMonths,
        int $nominalHours
    ) {
        $this->name = $name;
        $this->startDate = $startDate;
        $this->durationMonths = $durationMonths;
        $this->nominalHours = $nominalHours;
        $this->availableHours = $nominalHours;
        $this->status = $this->isDueToStart() ? PackageStatus::active() : PackageStatus::inactive();
    }

    public static function purchase(
        string $name,
        DateTime $startDate,
        PackageDurationMonths $durationMonths,
        int $nominalHours
    ) {
        return new self($name, $startDate, $durationMonths, $nominalHours);
    }

    private function isDueToStart()
    {
        return $this->startDate <= new DateTime();
    }
}
