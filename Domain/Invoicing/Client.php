<?php

namespace Mannion007\BestInvestments\Domain\Invoicing;

class Client
{
    private $clientId;
    private $payAsYouGoRate;

    public function __construct(ClientId $clientId, Money $payAsYouGoRate)
    {
        $this->clientId = $clientId;
        $this->payAsYouGoRate = $payAsYouGoRate;
    }
}
