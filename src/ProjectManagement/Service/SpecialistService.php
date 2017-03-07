<?php

namespace Mannion007\BestInvestments\ProjectManagement\Service;

use Mannion007\ValueObjects\Currency;
use Mannion007\BestInvestments\ProjectManagement\Domain\HourlyRate;
use Mannion007\BestInvestments\ProjectManagement\Domain\PotentialSpecialist;
use Mannion007\BestInvestments\ProjectManagement\Domain\SpecialistId;
use Mannion007\BestInvestments\ProjectManagement\Domain\ProjectManagerId;
use Mannion007\BestInvestments\ProjectManagement\Domain\SpecialistRepositoryInterface;
use Mannion007\BestInvestments\ProjectManagement\Domain\PotentialSpecialistRepositoryInterface;

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

    public function joinUpSpecialist(string $specialistId, string $hourlyRate): void
    {
        $potentialSpecialist = $this->potentialSpecialistRepository->getById(SpecialistId::fromExisting($specialistId));
        if (!$potentialSpecialist) {
            throw new \Exception(sprintf('Potential Specialist with id %s not found', $specialistId));
        }
        $this->specialistRepository->save($potentialSpecialist->register(new HourlyRate($hourlyRate, Currency::gbp())));
    }
}
