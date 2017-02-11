<?php

namespace Mannion007\BestInvestments\ProjectManagement\Domain;

interface PotentialSpecialistRepositoryInterface
{
    public function getById(SpecialistId $specialistId): PotentialSpecialist;
    public function save(PotentialSpecialist $potentialSpecialist);
}
