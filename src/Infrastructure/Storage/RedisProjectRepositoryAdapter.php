<?php

namespace Mannion007\BestInvestments\Infrastructure\Storage;

use Mannion007\BestInvestments\Domain\ProjectManagement\Project;
use Mannion007\BestInvestments\Domain\ProjectManagement\ProjectReference;
use Mannion007\BestInvestments\Domain\ProjectManagement\ProjectRepositoryInterface;

class RedisProjectRepositoryAdapter implements ProjectRepositoryInterface
{
    /** @var \Redis */
    private $redis;

    public function __construct(string $host, int $port)
    {
        $this->redis = new \Redis();
        $this->redis->connect($host, $port);
    }

    public function getByReference(ProjectReference $reference): Project
    {
        $project = $this->redis->get((string)$reference);
        if (!$project) {
            throw new \Exception(sprintf('Project with reference %s not found', $reference));
        }
        return unserialize($project);
    }

    public function save(Project $project): void
    {
        $this->redis->set((string)$project->getReference(), serialize($project));
    }

    public function purge()
    {
        $this->redis->flushAll();
    }
}
