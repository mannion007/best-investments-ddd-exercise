<?php

namespace Mannion007\BestInvestments\Domain\Prospecting;

use Mannion007\BestInvestments\Event\EventInterface;

class ProspectRegisteredEvent implements EventInterface
{
    const EVENT_NAME = 'prospect_registered';

    private $prospectId;
    private $hourlyRate;
    private $occurredAt;

    public function __construct($prospectId, $hourlyRate, \DateTimeInterface $occurredAt = null)
    {
        $this->prospectId = $prospectId;
        $this->hourlyRate = $hourlyRate;
        $this->occurredAt = is_null($occurredAt) ? new \DateTime() : $occurredAt;
    }

    public function getProspectId(): string
    {
        return $this->prospectId;
    }

    public function getHourlyRate(): string
    {
        return $this->hourlyRate;
    }

    public function getEventName(): string
    {
        return self::EVENT_NAME;
    }

    public function getOccurredAt(): \DateTimeInterface
    {
        return $this->occurredAt;
    }

    public function getPayload(): array
    {
        return['prospect_id' => $this->prospectId, 'hourly_rate' => $this->hourlyRate];
    }

    public static function fromPayload(array $payload): ProspectRegisteredEvent
    {
        return new self($payload['prospect_id'], $payload['hourly_rate']);
    }
}
