<?php

namespace Mannion007\BestInvestments\Domain\Invoicing;

class PackageReference
{
    const REFERENCE_FORMAT = '%s-%s-%s-%s';

    private $name;
    private $startDate;
    private $months;
    private $reference;

    public function __construct(string $name, \DateTimeInterface $startDate, PackageDuration $months)
    {
        $this->name = $name;
        $this->startDate = $startDate;
        $this->months = $months;
        $this->reference = sprintf(
            self::REFERENCE_FORMAT,
            $name,
            $startDate->format('Y'),
            $startDate->format('m'),
            (string)$months
        );
    }

    public function getStartDate(): \DateTimeInterface
    {
        return $this->startDate;
    }

    public function getDuration(): string
    {
        return $this->months;
    }

    public function getMonths(): PackageDuration
    {
        return $this->months;
    }

    public function __toString()
    {
        return (string)$this->reference;
    }
}
