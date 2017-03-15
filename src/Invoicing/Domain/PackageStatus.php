<?php

namespace Mannion007\BestInvestments\Invoicing\Domain;

class PackageStatus
{
    const ACTIVE = 'active';
    const NOT_YET_STARTED = 'not yet started';
    const EXPIRED = 'expired';

    /** @var string */
    private $status;

    private function __construct(string $status)
    {
        $this->status = $status;
    }

    public static function determineFrom(\DateTimeInterface $startDate, PackageLength $length): PackageStatus
    {
        $currentDate = new \DateTime();

        if ($startDate > $currentDate) {
            return new self(self::NOT_YET_STARTED);
        }

        $expiresAt = \DateTimeImmutable::createFromMutable($startDate)
            ->add(new \DateInterval(sprintf('P%sM', (string)$length)));

        if ($expiresAt < $currentDate) {
            return new self(self::EXPIRED);
        }

        return new self(self::ACTIVE);
    }

    public static function active(): PackageStatus
    {
        return new self(self::ACTIVE);
    }

    public static function notYetStarted(): PackageStatus
    {
        return new self(self::NOT_YET_STARTED);
    }

    public static function expired(): PackageStatus
    {
        return new self(self::EXPIRED);
    }

    public function isNot(PackageStatus $other): bool
    {
        return !$this->is($other);
    }

    public function is(PackageStatus $other): bool
    {
        return (string)$this === (string)$other;
    }

    public function __toString()
    {
        return (string)$this->status;
    }
}
