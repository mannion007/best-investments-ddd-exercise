<?php

namespace Mannion007\BestInvestments\Domain\Sales;

use Mannion007\BestInvestments\Event\EventInterface;

class ClientSignedUpEvent implements EventInterface
{
    const EVENT_NAME = 'client_signed_up';

    private $clientId;
    private $name;
    private $contactDetails;
    private $payAsYouGoRate;
    private $occurredAt;

    public function __construct($clientId, $name, $contactDetails, $payAsYouGoRate, \DateTime $occurredAt = null)
    {
        $this->clientId = $clientId;
        $this->name = $name;
        $this->contactDetails = $contactDetails;
        $this->payAsYouGoRate = $payAsYouGoRate;
        $this->occurredAt = is_null($occurredAt) ? new \DateTime() : $occurredAt;
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getContactDetails(): string
    {
        return $this->contactDetails;
    }

    public function getPayAsYouGoRate(): string
    {
        return $this->payAsYouGoRate;
    }

    public function getEventName(): string
    {
        return self::EVENT_NAME;
    }

    public function getOccurredAt(): \DateTime
    {
        return $this->occurredAt;
    }

    public function getPayload(): array
    {
        return
        [
            'client_id' => $this->clientId,
            'name' => $this->name,
            'contact_details' => $this->contactDetails,
            'pay_as_you_go_rate' => $this->payAsYouGoRate
        ];
    }

    public static function fromPayload(array $payload): ClientSignedUpEvent
    {
        return new self(
            $payload['client_id'],
            $payload['name'],
            $payload['contact_details'],
            $payload['pay_as_you_go_rate']
        );
    }
}
