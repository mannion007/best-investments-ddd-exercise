<?php

namespace Mannion007\BestInvestments\Event;

use Symfony\Component\EventDispatcher\GenericEvent;
use Mannion007\Interfaces\Event\EventInterface;

class TransactionSucceededEvent extends GenericEvent implements EventInterface
{
    const NAME = 'transaction_succeeded';

    public function __construct()
    {
        parent::__construct(self::NAME);
    }

    public function getEventName(): string
    {
        return $this->getSubject();
    }

    public function getPayload(): array
    {
        return $this->getArguments();
    }

    public function getOccurredAt(): \DateTimeInterface
    {
        return new \DateTime();
    }

    public static function fromPayload(array $payload)
    {
        return new self();
    }
}
