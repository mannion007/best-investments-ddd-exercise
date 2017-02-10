<?php

namespace Mannion007\BestInvestments\Infrastructure\Storage;

use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use Mannion007\BestInvestments\Domain\ProjectManagement\Specialist;
use Mannion007\BestInvestments\Domain\ProjectManagement\SpecialistId;
use Mannion007\BestInvestments\Domain\ProjectManagement\SpecialistRepositoryInterface;

class RedisSpecialistRepositoryAdapter implements SpecialistRepositoryInterface
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

    public function getById(SpecialistId $specialistId): Specialist
    {
        $specialist = $this->redis->get((string)$specialistId);
        if (!$specialist) {
            throw new \Exception(sprintf('Specialist with id %s not found', $specialistId));
        }
        return unserialize($specialist);
    }

    public function save(Specialist $specialist): void
    {
        $this->redis->set((string)$specialist->getSpecialistId(), serialize($specialist));
        $this->generateProjectView($specialist);
    }

    private function generateProjectView(Specialist $specialist): void
    {
        $this->redis->set(
            sprintf('%s-view', (string)$specialist->getSpecialistId()),
            $this->serializer->serialize($specialist, 'json')
        );
    }

    public function purge()
    {
        $this->redis->flushAll();
    }
}
