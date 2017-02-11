<?php

namespace Mannion007\BestInvestments\Event;

interface EventPublisherInterface
{
    public function publish(EventInterface $event);
}
