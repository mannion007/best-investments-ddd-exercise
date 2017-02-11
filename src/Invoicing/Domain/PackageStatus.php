<?php

namespace Mannion007\BestInvestments\Invoicing\Domain;

class PackageStatus
{
    const ACTIVE = 'active';
    const INACTIVE = 'inactive';
    const EXPIRED = 'expired';

    private $status;

    private function __construct(\DateTimeInterface $startDate, PackageDuration $duration)
    {
        $currentDate = new \DateTime();
        $expiryDate = new \DateTime('@'.strtotime('+'.(string)$duration.' month', $startDate->getTimestamp()));

        if ($currentDate < $startDate) {
            $this->status = self::INACTIVE;
        }
        if ($currentDate > $startDate) {
            $this->status = self::ACTIVE;
        }
        if ($currentDate > $expiryDate) {
            $this->status = self::EXPIRED;
        }
    }

    public static function determineFrom(\DateTimeInterface $startDate, PackageDuration $duration): PackageStatus
    {
        return new self($startDate, $duration);
    }

    public function isNot(string $status): bool
    {
        return !$this->is($status);
    }

    public function is(string $status): bool
    {
        return $status === $this->status;
    }
}
