<?php

namespace Mannion007\BestInvestments\Domain\Invoicing\Service;

use Mannion007\BestInvestments\Domain\Invoicing\PackageReference;
use Mannion007\BestInvestments\Domain\Invoicing\PackageRepositoryInterface;

class TimeTransferService
{
    public function transferAvailableTime(PackageReference $outReference, PackageReference $inReference)
    {
        $from = $packageRepository->getByReference($outReference);
        $into = $packageRepository->getByReference($inReference);
        $time = $from->transferOutTime();
        $into->transferInTime($time);
    }
}
