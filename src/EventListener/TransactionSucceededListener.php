<?php

namespace Mannion007\BestInvestments\EventListener;

use Mannion007\Interfaces\Event\EventInterface;
use Mannion007\Interfaces\EventListener\EventListenerInterface;
use Mannion007\Interfaces\EventPublisher\EventPublisherInterface;
use Mannion007\BestInvestments\EventPublisher\BufferPublisher;

class TransactionSucceededListener implements EventListenerInterface
{
    /** @var BufferPublisher */
    private $bufferPublisher;

    /** @var EventPublisherInterface */
    private $publisher;

    public function __construct(BufferPublisher $bufferPublisher, EventPublisherInterface $publisher)
    {
        $this->bufferPublisher = $bufferPublisher;
        $this->publisher = $publisher;
    }
    
    public function handle(EventInterface $event): void
    {
        foreach ($this->bufferPublisher->getEvents() as $event) {
            $this->publisher->publish($event);
        }
    }
}
