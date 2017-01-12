<?php

namespace Mannion007\BestInvestments\Domain\Invoicing\Repository;

interface PackageRepositoryInterface
{
    public function getByReference(PackageReference $reference);
}