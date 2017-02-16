<?php

namespace Mannion007\BestInvestments\ProjectManagement\Infrastructure\Storage;

use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use Mannion007\Interfaces\EventPublisher\EventPublisherInterface;
use Mannion007\BestInvestments\Event\TransactionSucceededEvent;
use Mannion007\BestInvestments\ProjectManagement\Domain\PotentialSpecialist;
use Mannion007\BestInvestments\ProjectManagement\Domain\SpecialistId;
use Mannion007\BestInvestments\ProjectManagement\Domain\PotentialSpecialistRepositoryInterface;

class RedisPotentialSpecialistRepositoryAdapter implements PotentialSpecialistRepositoryInterface
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

    public function getById(SpecialistId $specialistId): PotentialSpecialist
    {
        $potentialSpecialist = $this->redis->get(sprintf('potential-specialist-%s', (string)$specialistId));
        if (!$potentialSpecialist) {
            throw new \Exception(sprintf('Potential Specialist with id %s not found', $specialistId));
        }
        return unserialize($potentialSpecialist);
    }

    public function save(PotentialSpecialist $potentialSpecialist): void
    {
        $this->redis->set(
            sprintf('potential-specialist-%s', (string)$potentialSpecialist->getSpecialistId()),
            serialize($potentialSpecialist)
        );
        $this->generateProjectView($potentialSpecialist);
        $this->eventPublisher->publish(new TransactionSucceededEvent());
    }

    private function generateProjectView(PotentialSpecialist $potentialSpecialist): void
    {
        $this->redis->set(
            sprintf('potential-specialist-%s-view', (string)$potentialSpecialist->getSpecialistId()),
            $this->serializer->serialize($potentialSpecialist, 'json')
        );
    }

    public function purge()
    {
        $this->redis->flushAll();
    }
}
