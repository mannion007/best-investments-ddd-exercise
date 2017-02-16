<?php

namespace Mannion007\BestInvestments\ProjectManagement\Infrastructure\Storage;

use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use Mannion007\Interfaces\EventPublisher\EventPublisherInterface;
use Mannion007\BestInvestments\Event\TransactionSucceededEvent;
use Mannion007\BestInvestments\ProjectManagement\Domain\ClientId;
use Mannion007\BestInvestments\ProjectManagement\Domain\Project;
use Mannion007\BestInvestments\ProjectManagement\Domain\ProjectReference;
use Mannion007\BestInvestments\ProjectManagement\Domain\ProjectRepositoryInterface;

class RedisProjectRepositoryAdapter implements ProjectRepositoryInterface
{
    /** @var \Redis */
    private $redis;

    /** @var Serializer */
    private $serializer;

    /** @var EventPublisherInterface */
    private $eventPublisher;

    public function __construct(string $host, int $port, EventPublisherInterface $eventPublisher)
    {
        $this->redis = new \Redis();
        $this->redis->connect($host, $port);
        $this->serializer = SerializerBuilder::create()->build();
        $this->eventPublisher = $eventPublisher;
    }

    public function getByReference(ProjectReference $reference): Project
    {
        $project = $this->redis->get((string)$reference);
        if (!$project) {
            throw new \Exception(sprintf('Project with reference %s not found', $reference));
        }
        return unserialize($project);
    }

    public function getBelongingTo(ClientId $clientId): array
    {
        /** @var Project[] $allProjects */
        $allProjects = $this->redis->get('*');
        $belongingToClient = array_filter(
            $allProjects,
            function (ClientId $clientId, Project $project) {
                $reflected = new \ReflectionProperty($project, 'clientId');
                $reflected->setAccessible(true);
                return $reflected->getValue($project)->is($clientId);
            }
        );
        if (empty($belongingToClient)) {
            throw new \Exception(sprintf('No projects for Client with ID %s found', (string)$clientId));
        }
        return $belongingToClient;
    }

    public function save(Project $project): void
    {
        $this->redis->set((string)$project->getReference(), serialize($project));
        $this->generateProjectView($project);
        $this->eventPublisher->publish(new TransactionSucceededEvent());
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
