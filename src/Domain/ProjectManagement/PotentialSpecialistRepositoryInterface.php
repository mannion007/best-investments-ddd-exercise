<?php

namespace Mannion007\BestInvestments\Domain\ProjectManagement;

interface PotentialSpecialistRepositoryInterface
{
    public function getById(SpecialistId $specialistId): PotentialSpecialist;
    public function save(PotentialSpecialist $potentialSpecialist);
}
