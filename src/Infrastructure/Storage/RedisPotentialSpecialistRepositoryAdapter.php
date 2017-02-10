<?php

namespace Mannion007\BestInvestments\Infrastructure\Storage;

use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use Mannion007\BestInvestments\Domain\ProjectManagement\PotentialSpecialist;
use Mannion007\BestInvestments\Domain\ProjectManagement\SpecialistId;
use Mannion007\BestInvestments\Domain\ProjectManagement\PotentialSpecialistRepositoryInterface;

class RedisPotentialSpecialistRepositoryAdapter implements PotentialSpecialistRepositoryInterface
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

    public function getById(SpecialistId $specialistId): PotentialSpecialist
    {
        $potentialSpecialist = $this->redis->get((string)$specialistId);
        if (!$potentialSpecialist) {
            throw new \Exception(sprintf('Potential Specialist with id %s not found', $specialistId));
        }
        return unserialize($potentialSpecialist);
    }

    public function save(PotentialSpecialist $potentialSpecialist): void
    {
        $this->redis->set((string)$potentialSpecialist->getSpecialistId(), serialize($potentialSpecialist));
        $this->generateProjectView($potentialSpecialist);
    }

    private function generateProjectView(PotentialSpecialist $potentialSpecialist): void
    {
        $this->redis->set(
            sprintf('%s-view', (string)$potentialSpecialist->getSpecialistId()),
            $this->serializer->serialize($potentialSpecialist, 'json')
        );
    }

    public function purge()
    {
        $this->redis->flushAll();
    }
}
