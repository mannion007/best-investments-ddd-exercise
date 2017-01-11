<?php

class TimeTransferService
{
    public function transferAvailableTime(PackageReference $outReference, PackageReference $inReference)
    {
        $transferFrom = $packageRepository->getByReference($outReference);
        $transferTo = $packageRepository->getByReference($inReference);
        $transferredOutTime = $transferFrom->transferOutTime();
        $transferTo->transferInTime($transferredOutTime);
    }
}
