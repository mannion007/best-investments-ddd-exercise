<?php

namespace Mannion007\BestInvestments\Event;

interface EventListenerInterface
{
    public function handle(EventInterface $event): void;
}
