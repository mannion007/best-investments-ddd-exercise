<?php

namespace Mannion007\BestInvestments\Domain\Invoicing;

class TransferredOutTimeIncrement extends TimeIncrement
{
    private $clientId;

    public function __construct(ClientId $clientId, int $minutes)
    {
        parent::__construct($minutes);
        $this->clientId = $clientId;
    }

    public function doesNotBelongTo(ClientId $clientId): bool
    {
        return !$this->belongsTo($clientId);
    }

    public function belongsTo(ClientId $clientId): bool
    {
        return $clientId->is($this->clientId);
    }
}
