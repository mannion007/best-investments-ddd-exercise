<?php

namespace Mannion007\BestInvestments\Sales\Domain;

use Ramsey\Uuid\Uuid;

class ClientId
{
    private $clientId;

    public function __construct(string $clientId = null)
    {
        $this->clientId = is_null($clientId) ? Uuid::uuid4()->toString() : $clientId;
    }

    public static function fromExisting(string $clientId)
    {
        return new self($clientId);
    }

    public function isNot(ClientId $clientId): bool
    {
        return !$this->is($clientId);
    }

    public function is(ClientId $clientId): bool
    {
        return (string)$this === (string)$clientId;
    }

    public function __toString()
    {
        return (string)$this->clientId;
    }
}
