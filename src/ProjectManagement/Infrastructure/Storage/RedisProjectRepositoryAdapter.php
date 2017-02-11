<?php

namespace Mannion007\BestInvestments\ProjectManagement\Infrastructure\Storage;

use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use Mannion007\BestInvestments\ProjectManagement\Domain\Project;
use Mannion007\BestInvestments\ProjectManagement\Domain\ProjectReference;
use Mannion007\BestInvestments\ProjectManagement\Domain\ProjectRepositoryInterface;

class RedisProjectRepositoryAdapter implements ProjectRepositoryInterface
{
    /** @var \Redis */
    private $redis;

    /** @var Serializer */
    private $serializer;

    public function __construct(string $host, int $port)
    {
        $this->redis = new \Redis();
        $this->redis->connect($host, $port);
        $this->serializer = SerializerBuilder::create()->build();
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
        $this->generateProjectView($project);
    }

    private function generateProjectView(Project $project)
    {
        $this->redis->set(
            sprintf('%s-view', (string)$project->getReference()),
            $this->serializer->serialize($project, 'json')
        );
    }

    public function purge()
    {
        $this->redis->flushAll();
    }
}
