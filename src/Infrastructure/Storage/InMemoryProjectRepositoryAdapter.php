<?php

namespace Mannion007\BestInvestments\Storage;

use Mannion007\BestInvestments\Domain\ProjectManagement\Project;
use Mannion007\BestInvestments\Domain\ProjectManagement\ProjectReference;
use Mannion007\BestInvestments\Domain\ProjectManagement\ProjectRepositoryInterface;

class InMemoryProjectRepositoryAdapter implements ProjectRepositoryInterface
{
    private $items = [];

    public function getByReference(ProjectReference $reference)
    {
        $key = (string)$reference;
        return isset($this->items[$key]) ? $this->items[$key] : null;
    }

    public function save(Project $project)
    {
        $reflected = new \ReflectionClass($project);
        $this->items[(string)$reflected->getProperty('reference')->getValue()]
            = $reflected->getProperty('name')->getValue();
    }
}
