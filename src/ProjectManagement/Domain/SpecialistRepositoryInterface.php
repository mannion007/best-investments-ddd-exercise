<?php

namespace Mannion007\BestInvestments\ProjectManagement\Domain;

interface SpecialistRepositoryInterface
{
    public function getById(SpecialistId $specialistId);
    public function save(Specialist $specialist);
}
