<?php

namespace Mannion007\BestInvestments\Domain\Sales;

class PotentialClient
{
    private $clientId;
    private $name;
    private $contactDetails;

    private function __construct(string $name, ContactDetails $contactDetails)
    {
        $this->clientId = new ClientId();
        $this->name = $name;
        $this->contactDetails = $contactDetails;
    }

    public function signUp(Money $payAsYouGoRate)
    {
        return new Client($this->clientId, $this->name, $this->contactDetails, $payAsYouGoRate);
    }
}
