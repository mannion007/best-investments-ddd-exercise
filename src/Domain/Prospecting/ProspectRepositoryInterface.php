<?php

namespace Mannion007\BestInvestments\Domain\Prospecting;

interface ProspectRepositoryInterface
{
    public function getByProspectId(ProspectId $prospectId): Prospect;
    public function save(Prospect $prospect);
}
