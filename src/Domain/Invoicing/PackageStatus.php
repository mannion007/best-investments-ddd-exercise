<?php

namespace Mannion007\BestInvestments\Domain\Invoicing;

class PackageStatus
{
    const ACTIVE = 'active';
    const INACTIVE = 'inactive';
    const EXPIRED = 'expired';

    private $status;

    public static function determineFrom(\DateTime $startDate, PackageDuration $duration)
    {
        return new self($startDate, $duration);
    }

    private function __construct(\DateTime $startDate, PackageDuration $duration)
    {
        $currentDate = new \DateTime();
        $expiryDate = $startDate->add(new \DateInterval(sprintf('P%sM', (string)$duration)));

        if ($expiryDate <= $startDate) {
            throw new \DomainException('Package cannot expire before it starts');
        }
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

    public function isNot(string $status)
    {
        return !$this->is($status);
    }

    public function is(string $status)
    {
        return $status === $this->status;
    }
}
