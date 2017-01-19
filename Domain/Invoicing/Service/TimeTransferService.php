<?php

namespace Mannion007\BestInvestments\Domain\Invoicing\Service;

use Mannion007\BestInvestments\Domain\Invoicing\PackageReference;
use Mannion007\BestInvestments\Domain\Invoicing\PackageRepositoryInterface;

class TimeTransferService
{
    private $packageRepository;

    public function __construct(PackageRepositoryInterface $packageRepository)
    {
        $this->packageRepository = $packageRepository;
    }
    public function transferAvailableTime(PackageReference $fromReference, PackageReference $toReference)
    {
        $fromPackage = $this->packageRepository->getByReference($fromReference);
        $toPackage = $this->packageRepository->getByReference($toReference);
        $hours = $fromPackage->transferOutHours();
        $toPackage->transferInHours($hours);
    }
}
