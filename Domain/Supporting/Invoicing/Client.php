<?php

class Client
{
    private $clientId;
    private $payAsYouGoRate;

    /** "new client" sounds OK, no point in guarding the constructor */
    public function __construct(ClientId $clientId, Money $payAsYouGoRate)
    {
        $this->clientId = $clientId;
        $this->payAsYouGoRate = $payAsYouGoRate;
    }
}
