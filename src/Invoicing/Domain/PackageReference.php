<?php

namespace Mannion007\BestInvestments\Invoicing\Domain;

class PackageReference
{
    /** @var string  */
    private $name;

    /** @var \DateTimeInterface */
    private $startDate;

    /** @var PackageLength */
    private $length;

    public function __construct(string $name, \DateTimeInterface $startDate, PackageLength $length)
    {
        $this->name = $name;
        $this->startDate = $startDate;
        $this->length = $length;
    }

    public function getStartDate(): \DateTimeInterface
    {
        return $this->startDate;
    }

    public function getLength(): PackageLength
    {
        return $this->length;
    }

    public static function fromExisting(string $existing): PackageReference
    {
        $parts = explode('-', $existing);
        $name = $parts[0];
        $startDate = \DateTime::createFromFormat('Y-m-d', $parts[1] . '-' . $parts[2] . '-01');
        $length = PackageLength::fromExisting($parts[3]);
        return new self($name, $startDate, $length);
    }

    public function __toString()
    {
        return sprintf(
            '%s-%s-%s-%s',
            $this->name,
            $this->startDate->format('Y'),
            $this->startDate->format('m'),
            (string)$this->length
        );
    }
}
