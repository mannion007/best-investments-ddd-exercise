<?php

namespace Mannion007\BestInvestments\Invoicing\Domain;

class TransferTime
{
    private $clientId;
    private $time;

    public function __construct(ClientId $clientId, int $minutes)
    {
        $this->clientId = $clientId;
        $this->time = new TimeIncrement($minutes);
    }

    public function doesNotBelongTo(ClientId $clientId): bool
    {
        return !$this->belongsTo($clientId);
    }

    public function belongsTo(ClientId $clientId): bool
    {
        return $clientId->is($this->clientId);
    }

    public function getTime(): TimeIncrement
    {
        return $this->time;
    }
}
