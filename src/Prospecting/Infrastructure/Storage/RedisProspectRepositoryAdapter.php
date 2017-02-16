<?php

namespace Mannion007\BestInvestments\Prospecting\Infrastructure\Storage;

use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use Mannion007\Interfaces\EventPublisher\EventPublisherInterface;
use Mannion007\BestInvestments\Event\TransactionSucceededEvent;
use Mannion007\BestInvestments\Prospecting\Domain\Prospect;
use Mannion007\BestInvestments\Prospecting\Domain\ProspectId;
use Mannion007\BestInvestments\Prospecting\Domain\ProspectRepositoryInterface;

class RedisProspectRepositoryAdapter implements ProspectRepositoryInterface
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

    public function getByProspectId(ProspectId $prospectId): Prospect
    {
        $prospect = $this->redis->get((string)$prospectId);
        if (!$prospect) {
            throw new \Exception(sprintf('Prospect with id %s not found', $prospectId));
        }
        return unserialize($prospect);
    }

    public function save(Prospect $prospect): void
    {
        $this->redis->set((string)$prospect->getProspectId(), serialize($prospect));
        $this->generateProjectView($prospect);
        $this->eventPublisher->publish(new TransactionSucceededEvent());
    }

    private function generateProjectView(Prospect $prospect): void
    {
        $this->redis->set(
            sprintf('%s-view', (string)$prospect->getProspectId()),
            $this->serializer->serialize($prospect, 'json')
        );
    }

    public function purge()
    {
        $this->redis->flushAll();
    }
}
