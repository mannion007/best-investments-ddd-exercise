<?php

namespace Mannion007\BestInvestments\Domain\ProjectManagement;

class DomainEvent
{
    const EVENT_NAME = null;

    protected $occurredAt;

    public function __construct(\DateTime $occurredAt = null)
    {
        $this->occurredAt = is_null($occurredAt) ? new \DateTime() : $occurredAt;
    }

    public function getEventName() : string
    {
        return static::EVENT_NAME;
    }

    public function getOccurredAt() : \DateTime
    {
        return $this->occurredAt;
    }
}
