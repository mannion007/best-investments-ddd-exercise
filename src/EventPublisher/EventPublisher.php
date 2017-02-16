<?php

namespace Mannion007\BestInvestments\EventPublisher;

use Mannion007\Interfaces\Event\EventInterface;
use Mannion007\Interfaces\EventPublisher\EventPublisherInterface;

class EventPublisher
{
    /** @var EventPublisherInterface */
    private static $publisher;

    public static function registerPublisher(EventPublisherInterface $publisher)
    {
        self::$publisher = $publisher;
    }

    public static function publish(EventInterface $event)
    {
        self::$publisher->publish($event);
    }
}
