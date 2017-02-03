<?php

namespace Mannion007\BestInvestments\Application;

use Mannion007\BestInvestments\Domain\ProjectManagement\ProjectRepositoryInterface;
use Mannion007\BestInvestments\Domain\ProjectManagement\Project;
use Mannion007\BestInvestments\Domain\ProjectManagement\ProjectReference;
use Mannion007\BestInvestments\Domain\ProjectManagement\ConsultationId;
use Mannion007\BestInvestments\Domain\ProjectManagement\ClientId;
use Mannion007\BestInvestments\Domain\ProjectManagement\ProjectManagerId;
use Mannion007\BestInvestments\Domain\ProjectManagement\SpecialistId;

class ProjectService
{
    private $projectRepository;

    public function __construct(ProjectRepositoryInterface $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    public function setUpProject(string $clientId, string $name, string $deadline): string
    {
        $dead = new \DateTime();
        $project = Project::setUp(ClientId::fromExisting($clientId), $name, $dead);
        $this->projectRepository->save($project);
        return (string)$project->getReference();
    }

    public function startProject(string $projectReference, string $projectManagerId): void
    {
        $project = $this->projectRepository->getByReference(ProjectReference::fromExisting($projectReference));
        $project->start(ProjectManagerId::fromExisting($projectManagerId));
        $this->projectRepository->save($project);
    }

    public function closeProject(string $projectReference): void
    {
        $project = $this->projectRepository->getByReference(ProjectReference::fromExisting($projectReference));
        $project->close();
        $this->projectRepository->save($project);
    }

    public function addSpecialistToProject(string $projectReference, string $specialistId): void
    {
        $project = $this->projectRepository->getByReference(ProjectReference::fromExisting($projectReference));
        $project->addSpecialist(SpecialistId::fromExisting($specialistId));
        $this->projectRepository->save($project);
    }

    public function discardSpecialistFromProject(string $projectReference, string $specialistId): void
    {
        $project = $this->projectRepository->getByReference(ProjectReference::fromExisting($projectReference));
        $project->discardSpecialist(SpecialistId::fromExisting($specialistId));
        $this->projectRepository->save($project);
    }

    public function scheduleConsultationForProject(
        string $projectReference,
        string $specialistId,
        \DateTimeInterface $time
    ): void {
        $project = $this->projectRepository->getByReference(ProjectReference::fromExisting($projectReference));
        $project->scheduleConsultation(SpecialistId::fromExisting($specialistId), $time);
        $this->projectRepository->save($project);
    }

    public function reportConsultationOnProject(
        string $projectReference,
        string $consultationId,
        int $durationMinutes
    ): void {
        $project = $this->projectRepository->getByReference(ProjectReference::fromExisting($projectReference));
        $project->reportConsultation(ConsultationId::fromExisting($consultationId), $durationMinutes);
        $this->projectRepository->save($project);
    }

    public function discardConsultationOnProject(string $projectReference, string $consultationId): void
    {
        $project = $this->projectRepository->getByReference(ProjectReference::fromExisting($projectReference));
        $project->discardConsultation(ConsultationId::fromExisting($consultationId));
        $this->projectRepository->save($project);
    }

    public function putProjectOnHold(string $projectReference): void
    {
        $project = $this->projectRepository->getByReference(ProjectReference::fromExisting($projectReference));
        $project->putOnHold();
        $this->projectRepository->save($project);
    }

    public function reactivateProject(string $projectReference): void
    {
        $project = $this->projectRepository->getByReference(ProjectReference::fromExisting($projectReference));
        $project->reactivate();
        $this->projectRepository->save($project);
    }
}
