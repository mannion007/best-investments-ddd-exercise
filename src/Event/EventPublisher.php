<?php

namespace Mannion007\BestInvestments\Event;

class EventPublisher
{
    /** @var EventHandlerInterface */
    private static $handler;

    public static function registerHandler(EventHandlerInterface $handler)
    {
        /** This needs to be called before any requests can be handled */
        self::$handler = $handler;
    }

    public static function publish(EventInterface $event)
    {
        self::$handler->handle($event);
    }
}
