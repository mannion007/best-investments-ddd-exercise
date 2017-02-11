<?php

namespace Mannion007\BestInvestments\Invoicing\Domain;

class ClientId
{
    private $clientId;

    private function __construct(string $clientId)
    {
        $this->clientId = $clientId;
    }

    public static function fromExisting(string $clientId): ClientId
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
