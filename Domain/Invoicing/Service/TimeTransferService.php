<?php

namespace Mannion007\BestInvestments\Domain\Invoicing\Service;

use Mannion007\BestInvestments\Domain\Invoicing\Package;
use Mannion007\BestInvestments\Domain\Invoicing\PackageReference;
use Mannion007\BestInvestments\Domain\Invoicing\PackageRepositoryInterface;

class TimeTransferService
{
    public function transferAvailableTime(PackageReference $fromReference, PackageReference $toReference)
    {
        /** @var Package $fromPackage */
        $fromPackage = $packageRepository->getByReference($fromReference);
        /** @var Package $toPackage */
        $toPackage = $packageRepository->getByReference($toReference);
        $hours = $fromPackage->transferOutHours();
        $toPackage->transferInHours($hours);
    }
}
