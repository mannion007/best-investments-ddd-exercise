<?php

namespace Mannion007\BestInvestments\EventPublisher;

use Mannion007\Interfaces\Event\EventInterface;
use Mannion007\Interfaces\EventPublisher\EventPublisherInterface;

class BufferPublisher implements EventPublisherInterface
{
    private $events = [];

    public function publish(EventInterface $event)
    {
        $this->events[] = $event;
    }

    public function getEvents()
    {
        return $this->events;
    }
}
