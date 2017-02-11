<?php

namespace Mannion007\BestInvestments\Invoicing\Domain;

interface PackageRepositoryInterface
{
    public function getByReference(PackageReference $reference): Package;
    public function save(Package $package);
}
