<?php

namespace Mannion007\BestInvestments\Domain\ProjectManagement;

interface ProjectRepositoryInterface
{
    public function getByReference(ProjectReference $reference): Project;
    public function save(Project $project): void;
}
