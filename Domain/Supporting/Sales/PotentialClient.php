<?php

class PotentialClient
{
    private $clientId;
    private $contactDetails;

    /** "new potential client" sounds OK, no point in guarding the constructor */
    private function __construct(ClientId $clientId, PotentialSpecialistContactDetails $contactDetails)
    {
        $this->clientId = $clientId;
        $this->contactDetails = $contactDetails;
    }

    public function signUp(Money $payAsYouGoRate)
    {
        return new Client($this->clientId, $payAsYouGoRate);
    }
}
