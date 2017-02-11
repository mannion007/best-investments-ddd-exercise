<?php

namespace Mannion007\BestInvestments\ProjectManagement\Application;

use Mannion007\BestInvestments\ProjectManagement\Domain\ProjectRepositoryInterface;
use Mannion007\BestInvestments\ProjectManagement\Domain\Project;
use Mannion007\BestInvestments\ProjectManagement\Domain\ProjectReference;
use Mannion007\BestInvestments\ProjectManagement\Domain\ConsultationId;
use Mannion007\BestInvestments\ProjectManagement\Domain\ClientId;
use Mannion007\BestInvestments\ProjectManagement\Domain\ProjectManagerId;
use Mannion007\BestInvestments\ProjectManagement\Domain\SpecialistId;

class ProjectService
{
    private $projectRepository;

    public function __construct(ProjectRepositoryInterface $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    public function setUpProject(string $clientId, string $name, string $deadline): string
    {
        $clientId = ClientId::fromExisting($clientId);
        $deadline = \DateTime::createFromFormat('Y-m-d', $deadline);
        $project = Project::setUp($clientId, $name, $deadline);
        $this->projectRepository->save($project);
        return (string)$project->getReference();
    }

    public function startProject(string $reference, string $projectManagerId): void
    {
        $project = $this->projectRepository->getByReference(ProjectReference::fromExisting($reference));
        $project->start(ProjectManagerId::fromExisting($projectManagerId));
        $this->projectRepository->save($project);
    }

    public function closeProject(string $reference): void
    {
        $project = $this->projectRepository->getByReference(ProjectReference::fromExisting($reference));
        $project->close();
        $this->projectRepository->save($project);
    }

    public function addSpecialistToProject(string $reference, string $specialistId): void
    {
        $project = $this->projectRepository->getByReference(ProjectReference::fromExisting($reference));
        $project->addSpecialist(SpecialistId::fromExisting($specialistId));
        $this->projectRepository->save($project);
    }

    public function approveSpecialistForProject(string $reference, string $specialistId): void
    {
        $project = $this->projectRepository->getByReference(ProjectReference::fromExisting($reference));
        $project->approveSpecialist(SpecialistId::fromExisting($specialistId));
        $this->projectRepository->save($project);
    }

    public function discardSpecialistFromProject(string $reference, string $specialistId): void
    {
        $project = $this->projectRepository->getByReference(ProjectReference::fromExisting($reference));
        $project->discardSpecialist(SpecialistId::fromExisting($specialistId));
        $this->projectRepository->save($project);
    }

    public function scheduleConsultationForProject(string $reference, string $specialistId, string $time): int
    {
        $project = $this->projectRepository->getByReference(ProjectReference::fromExisting($reference));
        $consultationId = $project->scheduleConsultation(
            SpecialistId::fromExisting($specialistId),
            \DateTime::createFromFormat('Y-m-d', $time)
        );
        $this->projectRepository->save($project);
        return (string)$consultationId;
    }

    public function reportConsultationOnProject(
        string $reference,
        string $consultationId,
        string $durationMinutes
    ): void {
        $project = $this->projectRepository->getByReference(ProjectReference::fromExisting($reference));
        $project->reportConsultation(ConsultationId::fromExisting((int)$consultationId), (int)$durationMinutes);
        $this->projectRepository->save($project);
    }

    public function discardConsultationFromProject(string $reference, string $consultationId): void
    {
        $project = $this->projectRepository->getByReference(ProjectReference::fromExisting($reference));
        $project->discardConsultation(ConsultationId::fromExisting((int)$consultationId));
        $this->projectRepository->save($project);
    }

    public function putProjectOnHold(string $reference): void
    {
        $project = $this->projectRepository->getByReference(ProjectReference::fromExisting($reference));
        $project->putOnHold();
        $this->projectRepository->save($project);
    }

    public function reactivateProject(string $reference): void
    {
        $project = $this->projectRepository->getByReference(ProjectReference::fromExisting($reference));
        $project->reactivate();
        $this->projectRepository->save($project);
    }
}
