<?php

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
