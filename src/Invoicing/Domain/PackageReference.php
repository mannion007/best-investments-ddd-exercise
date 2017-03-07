<?php

namespace Mannion007\BestInvestments\Invoicing\Domain;

class PackageReference
{
    const REFERENCE_FORMAT = '%s-%s-%s-%s';

    private $startDate;
    private $months;
    private $reference;

    public function __construct(string $name, \DateTimeInterface $startDate, PackageDuration $months)
    {
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

    public function getMonths(): PackageDuration
    {
        return $this->months;
    }

    public static function fromExisting(string $existing): PackageReference
    {
        $parts = explode('-', $existing);
        return new self(
            $parts[0],
            new \DateTime(sprintf('%s-%s-01', $parts[1], $parts[2])),
            PackageDuration::fromExisting($parts[3])
        );
    }

    public function __toString()
    {
        return (string)$this->reference;
    }
}
