<?php

namespace Mannion007\BestInvestments\Sales\Domain;

use Mannion007\Interfaces\Event\EventInterface;

class PackagePurchasedEvent implements EventInterface
{
    const EVENT_NAME = 'package_purchased';

    private $reference;
    private $clientId;
    private $startDate;
    private $nominalHours;
    private $occurredAt;

    public function __construct($reference, $clientId, $startDate, $nominalHours, \DateTimeInterface $occurredAt = null)
    {
        $this->reference = $reference;
        $this->clientId = $clientId;
        $this->startDate = $startDate;
        $this->nominalHours = $nominalHours;
        $this->occurredAt = is_null($occurredAt) ? new \DateTime() : $occurredAt;
    }

    public function getReference(): PackageReference
    {
        return $this->reference;
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function getNominalHours(): string
    {
        return $this->nominalHours;
    }

    public function getStartDate(): string
    {
        return $this->startDate;
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
        return
        [
            'reference' => $this->reference,
            'client_id' => $this->clientId,
            'start_date' => $this->startDate,
            'nominal_hours' => $this->nominalHours
        ];
    }

    public static function fromPayload(array $payload): PackagePurchasedEvent
    {
        return new self(
            $payload['reference'],
            $payload['client_id'],
            $payload['start_date'],
            $payload['nominal_hours']
        );
    }
}
