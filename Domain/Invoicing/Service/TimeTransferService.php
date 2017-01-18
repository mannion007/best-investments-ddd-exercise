<?php

namespace Mannion007\BestInvestments\Domain\Invoicing\Service;

use Mannion007\BestInvestments\Domain\Invoicing\PackageReference;
use Mannion007\BestInvestments\Domain\Invoicing\PackageRepositoryInterface;

class TimeTransferService
{
    private $repository;

    public function __construct(PackageRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
    public function transferAvailableTime(PackageReference $fromReference, PackageReference $toReference)
    {
        $fromPackage = $this->repository->getByReference($fromReference);
        $toPackage = $this->repository->getByReference($toReference);
        $hours = $fromPackage->transferOutHours();
        $toPackage->transferInHours($hours);
    }
}
