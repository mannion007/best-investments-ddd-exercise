<?php

namespace Mannion007\BestInvestments\Invoicing\Domain;

class TransferTime extends TimeIncrement
{
    private $clientId;

    public function __construct(int $minutes, ClientId $clientId)
    {
        parent::__construct($minutes);
        $this->clientId = $clientId;
    }

    public function doesNotBelongTo(ClientId $other): bool
    {
        return $this->clientId->isNot($other);
    }
}
