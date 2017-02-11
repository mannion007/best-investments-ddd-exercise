<?php

namespace Mannion007\BestInvestments\Prospecting\Domain;

interface ProspectRepositoryInterface
{
    public function getByProspectId(ProspectId $prospectId): Prospect;
    public function save(Prospect $prospect);
}
