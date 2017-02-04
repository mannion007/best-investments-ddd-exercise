<?php

namespace Mannion007\BestInvestments\Infrastructure\Storage;

use Mannion007\BestInvestments\Domain\ProjectManagement\Project;
use Mannion007\BestInvestments\Domain\ProjectManagement\ProjectReference;
use Mannion007\BestInvestments\Domain\ProjectManagement\ProjectRepositoryInterface;

class InMemoryProjectRepositoryAdapter implements ProjectRepositoryInterface
{
    private $items = [];

    public function getByReference(ProjectReference $reference): Project
    {
        if (!isset($this->items[(string)$reference])) {
            throw new \Exception(
                sprintf('Project with reference %s not found', $reference)
            );
        }
        return $this->items[(string)$reference];
    }

    public function save(Project $project): void
    {
        $this->items[(string)$project->getReference()] = $project;
    }
}
