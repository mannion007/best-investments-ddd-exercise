<?php

namespace Mannion007\BestInvestments\Sales\Domain;

use Mannion007\BestInvestments\EventPublisher\EventPublisher;

class Client
{
    private $clientId;
    private $name;
    private $contactDetails;
    private $payAsYouGoRate;
    private $status;

    public function __construct(ClientId $clientId, string $name, ContactDetails $contactDetails, Money $payAsYouGoRate)
    {
        $this->clientId = $clientId;
        $this->name = $name;
        $this->contactDetails = $contactDetails;
        $this->payAsYouGoRate = $payAsYouGoRate;
        $this->status = ClientStatus::active();

        EventPublisher::publish(
            new ClientSignedUpEvent(
                (string)$this->clientId,
                $this->name,
                (string)$this->contactDetails,
                (string)$this->payAsYouGoRate
            )
        );
    }

    public function purchasePackage(
        string $name,
        \DateTimeInterface $startDate,
        PackageDuration $months,
        int $nominalHours
    ) {
        return new Package($this->clientId, $name, $startDate, $months, $nominalHours);
    }

    public function suspendService()
    {
        if ($this->status->is(ClientStatus::SUSPENDED)) {
            throw new \Exception('Cannot suspend the Service of a Client when it is already suspended');
        }
        $this->status = ClientStatus::suspended();
        EventPublisher::publish(new ServiceSuspendedEvent((string)$this->clientId));
    }

    public function resumeOperations()
    {
        if ($this->status->isNot(ClientStatus::SUSPENDED)) {
            throw new \Exception('Cannot resume operations of a Client that is not suspended');
        }
        $this->status = ClientStatus::active();
        EventPublisher::publish(new OperationsResumedEvent((string)$this->clientId));
    }
}
