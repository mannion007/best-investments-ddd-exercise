<?php

namespace Mannion007\BestInvestments\ProjectManagement\Infrastructure\Storage;

use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use Mannion007\Interfaces\EventPublisher\EventPublisherInterface;
use Mannion007\BestInvestments\Event\TransactionSucceededEvent;
use Mannion007\BestInvestments\ProjectManagement\Domain\Specialist;
use Mannion007\BestInvestments\ProjectManagement\Domain\SpecialistId;
use Mannion007\BestInvestments\ProjectManagement\Domain\SpecialistRepositoryInterface;

class RedisSpecialistRepositoryAdapter implements SpecialistRepositoryInterface
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
        $this->eventPublisher->publish(new TransactionSucceededEvent());
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
