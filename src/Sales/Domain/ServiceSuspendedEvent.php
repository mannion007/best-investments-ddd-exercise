<?php

namespace Mannion007\BestInvestments\Sales\Domain;

use Mannion007\Interfaces\Event\EventInterface;

class ServiceSuspendedEvent implements EventInterface
{
    const EVENT_NAME = 'service_suspended';

    private $clientId;
    private $occurredAt;

    public function __construct($clientId, \DateTimeInterface $occurredAt = null)
    {
        $this->clientId = $clientId;
        $this->occurredAt = is_null($occurredAt) ? new \DateTime() : $occurredAt;
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function getEventName(): string
    {
        return self::EVENT_NAME;
    }

    public function getPayload(): array
    {
        return ['client_id' => $this->clientId];
    }

    public function getOccurredAt(): \DateTimeInterface
    {
        return $this->occurredAt;
    }

    public static function fromPayload(array $payload): ServiceSuspendedEvent
    {
        return new self($payload['client_id']);
    }
}
