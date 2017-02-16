<?php

namespace Mannion007\BestInvestments\EventPublisher;

use Mannion007\Interfaces\Event\EventInterface;
use Mannion007\Interfaces\EventPublisher\EventPublisherInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

class SymfonyDispatcherEventPublisher implements EventPublisherInterface
{
    /** @var EventDispatcher */
    private $dispatcher;

    public function __construct(EventDispatcher $eventDispatcher)
    {
        $this->dispatcher = $eventDispatcher;
    }

    public function publish(EventInterface $event)
    {
        $this->dispatcher->dispatch($event->getEventName(), $event);
    }
}
