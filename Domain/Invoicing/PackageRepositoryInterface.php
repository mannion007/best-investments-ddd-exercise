<?php

namespace Mannion007\BestInvestments\Domain\Invoicing;

interface PackageRepositoryInterface
{
    public function getByReference(PackageReference $reference) : Package;
}