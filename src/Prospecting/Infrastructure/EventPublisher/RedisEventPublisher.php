<?php

namespace Mannion007\BestInvestments\Prospecting\Infrastructure\EventPublisher;

use Mannion007\Interfaces\Event\EventInterface;
use Mannion007\Interfaces\EventPublisher\EventPublisherInterface;

class RedisEventPublisher implements EventPublisherInterface
{
    /** @var \Redis */
    private $redis;

    public function __construct(string $host, int $port)
    {
        $this->redis = new \Redis();
        $this->redis->connect($host, $port);
    }

    public function publish(EventInterface $event)
    {
        $this->redis->set($event->getEventName(), serialize($event));
    }

    public function hasNotPublished(string $eventName): bool
    {
        return !$this->hasPublished($eventName);
    }

    public function hasPublished(string $eventName): bool
    {
        return false !== $this->redis->get($eventName);
    }
}
