<?php

namespace Mannion007\BestInvestments\Event;

interface EventHandlerInterface
{
    public function handle(EventInterface $domainEvent);
}
