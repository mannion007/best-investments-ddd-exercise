<?php

namespace Mannion007\BestInvestments\Invoicing\Domain;

class Client
{
    private $clientId;
    private $hourlyRate;

    private function __construct(ClientId $clientId, HourlyRate $hourlyRate)
    {
        $this->clientId = $clientId;
        $this->hourlyRate = $hourlyRate;
    }

    public static function signUp(ClientId $clientId, HourlyRate $hourlyRate)
    {
        return new self($clientId, $hourlyRate);
    }
}
