<?php

class PackageReference
{
    const REFERENCE_FORMAT = '%s-%s-%s-%s';

    private $reference;

    public function __construct(
        string $name,
        DateTime $startDate,
        PackageDurationMonths $durationMonths
    ) {
        $this->reference = sprintf(
            self::REFERENCE_FORMAT,
            $name,
            $startDate->format('Y'),
            $startDate->format('m'),
            (string)$durationMonths
        );
    }

    public function __toString()
    {
        return (string)$this->reference;
    }
}
