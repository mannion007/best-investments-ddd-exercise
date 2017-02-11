<?php

namespace Mannion007\BestInvestments\Sales\Domain;

use Mannion007\BestInvestments\Event\EventInterface;

class OperationsResumedEvent implements EventInterface
{
    const EVENT_NAME = 'operations_resumed';

    private $clientId;
    private $occurredAt;

    public function __construct($clientId, \DateTimeInterface $occurredAt = null)
    {
        $this->clientId = $clientId;
        $this->occurredAt = is_null($occurredAt) ? new \DateTime() : $occurredAt;
    }

    public function getEventName(): string
    {
        return self::EVENT_NAME;
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function getOccurredAt(): \DateTimeInterface
    {
        return $this->occurredAt;
    }

    public function getPayload(): array
    {
        return ['client_id' => $this->clientId];
    }

    public static function fromPayload(array $payload): OperationsResumedEvent
    {
        return new self($payload['client_id']);
    }
}
