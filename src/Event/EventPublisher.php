<?php

namespace Mannion007\BestInvestments\Event;

class EventPublisher
{
    /** @var EventPublisherInterface */
    private static $publisher;

    public static function registerPublisher(EventPublisherInterface $publisher)
    {
        /** This needs to be called before any requests can be handled */
        self::$publisher = $publisher;
    }

    public static function publish(EventInterface $event)
    {
        self::$publisher->publish($event);
    }
}
