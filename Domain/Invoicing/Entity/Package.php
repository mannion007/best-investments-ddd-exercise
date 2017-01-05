<?php

class Package
{
    const REFERENCE_FORMAT = '%s-%s-%s-%s';

    private $name;
    private $startDate;
    private $durationMonths;
    private $availableHours;
    private $status;

    /** Custom type for this? */
    private $projects = [];

    public function closeDownManually()
    {
        if ($this->status->isNot(PackageStatus::ACTIVE)) {
            throw new Exception('Cannot close package that is not active');
        }
        $this->status = PackageStatus::closed();

        /**
         * If this has negative available hours, that number will either be applied to another package or invoiced
         * as P-A-Y-G.
         * If this has hours remaining, the hours will be added to another package ONLY (Not deduced from P-A-Y-G)
         * Either way, it probably needs to happen manually, so just raise an event
         */

        /** Raise a package_closed_down_manually_event */
    }

    public function assignHours(int $hours)
    {
        if ($this->status->isNot(PackageStatus::INACTIVE)) {
            throw new Exception('Can only assign hours to a package that has not started yet');
        }
        $this->availableHours += $hours;
    }

    public function expire()
    {
        if ($this->status->isNot(PackageStatus::Active)) {
            throw new Exception('Can not expire Package because it is not active');
        }
        if (!$this->isDueToExpire()) {
            throw new Exception('Package is not yet due to expire');
        }
        $this->status = PackageStatus::expired();
        /** Raise a package expired event that includes the available hours */
    }

    private function isDueToExpire()
    {
        return $this->startDate->add(new DateInterval(sprintf('P%sM', $this->durationMonths))) >= new DateTime();
    }

    public function attach(ClosedProject $project)
    {
        if ($this->status->isNot(PackageStatus::ACTIVE)) {
            throw new Exception('Cannot attach a Project to a Package that is not active');
        }
        $this->projects[] = $project;
        /** Is this the right name? */
        $this->availableHours -= $project->getTotalConsultationTime();

        if ($this->availableHours <= 0) {
            $this->status = ProjectStatus::closed();
            /** Raise a project_closed event */
        }
    }

    /**
     * Invoicing team use package references
     */
    public function getReference()
    {
        return sprintf(
            self::REFERENCE_FORMAT,
            $this->name,
            $this->startDate->format('Y'),
            $this->startDate->format('m'),
            (string)$this->durationMonths
        );
    }
}
