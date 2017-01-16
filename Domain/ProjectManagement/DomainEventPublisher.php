<?php

namespace Mannion007\BestInvestments\Domain\ProjectManagement;

class DomainEventPublisher
{
    /** @var HandlerInterface */
    private static $handler;


    public static function registerHandler(HandlerInterface $handler)
    {
        /** This needs to be called before any requests can be handled */
        self::$handler = $handler;
    }

    public static function publish(DomainEvent $event)
    {
        self::$handler->handle($event);
    }
}
