<?php

namespace Mannion007\BestInvestments\ProjectManagement\Infrastructure\EventPublisher;

use Mannion007\BestInvestments\Event\EventPublisherInterface;
use Mannion007\BestInvestments\Event\EventInterface;

class InMemoryEventPublisher implements EventPublisherInterface
{
    private $events = [];

    public function publish(EventInterface $event)
    {
        $this->events[$event->getEventName()] = $event;
    }

    public function hasNotPublished(string $eventName): bool
    {
        return !$this->hasPublished($eventName);
    }

    public function hasPublished(string $eventName): bool
    {
        return array_key_exists($eventName, $this->events);
    }
}
