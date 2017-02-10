<?php

namespace Mannion007\BestInvestments\Domain\ProjectManagement;

interface SpecialistRepositoryInterface
{
    public function getById(SpecialistId $specialistId);
    public function save(Specialist $specialist);
}
