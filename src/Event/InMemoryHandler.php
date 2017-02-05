<?php

namespace Mannion007\BestInvestments\Event;

class InMemoryHandler implements EventHandlerInterface
{
    private $events = [];

    public function handle(EventInterface $event)
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
