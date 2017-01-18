<?php

namespace Mannion007\BestInvestments\Domain\Prospecting;

interface ProspectRepositoryInterface
{
    public function getByProspectId(ProspectId $prospectId);
    public function save(Prospect $prospect);
}
