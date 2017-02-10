<?php

namespace Mannion007\BestInvestments\Application;

use Mannion007\BestInvestments\Domain\ProjectManagement\Money;
use Mannion007\BestInvestments\Domain\ProjectManagement\PotentialSpecialist;
use Mannion007\BestInvestments\Domain\ProjectManagement\SpecialistId;
use Mannion007\BestInvestments\Domain\ProjectManagement\ProjectManagerId;
use Mannion007\BestInvestments\Domain\ProjectManagement\SpecialistRepositoryInterface;
use Mannion007\BestInvestments\Domain\ProjectManagement\PotentialSpecialistRepositoryInterface;

class SpecialistService
{
    private $potentialSpecialistRepository;
    private $specialistRepository;

    public function __construct(
        PotentialSpecialistRepositoryInterface $potentialSpecialistRepository,
        SpecialistRepositoryInterface $specialistRepository
    ) {
        $this->potentialSpecialistRepository = $potentialSpecialistRepository;
        $this->specialistRepository = $specialistRepository;
    }

    public function putPotentialSpecialistOnList(string $projectManagerId, string $name, string $notes): string
    {
        $potentialSpecialist = PotentialSpecialist::putOnList(
            ProjectManagerId::fromExisting($projectManagerId),
            $name,
            $notes
        );
        $this->potentialSpecialistRepository->save($potentialSpecialist);
        return (string)$potentialSpecialist->getSpecialistId();
    }

    public function joinUpSpecialist(string $specialistId, int $hourlyRate): void
    {
        $potentialSpecialist = $this->potentialSpecialistRepository->getById(SpecialistId::fromExisting($specialistId));
        if (!$potentialSpecialist) {
            throw new \Exception(sprintf('Potential Specialist with id %s not found', $specialistId));
        }
        $this->specialistRepository->save($potentialSpecialist->register(new Money($hourlyRate)));
    }
}
