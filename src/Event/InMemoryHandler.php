<?php

namespace Mannion007\BestInvestments\Event;

class InMemoryHandler implements EventHandlerInterface
{
    private $events = [];

    public function handle(EventInterface $event)
    {
        $this->events[$event->getEventName()] = $event;
    }

    public function hasPublished(string $eventName)
    {
        return array_key_exists($eventName, $this->events);
    }
}
