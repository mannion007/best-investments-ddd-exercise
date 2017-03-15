<?php

namespace Mannion007\BestInvestments\Invoicing\Domain;

class ClientId
{
    /** @var string */
    private $clientId;

    private function __construct(string $existing)
    {
        $this->clientId = $existing;
    }

    public static function fromExisting(string $existing): ClientId
    {
        return new self($existing);
    }

    public function isNot(ClientId $other): bool
    {
        return !$this->is($other);
    }

    public function is(ClientId $other): bool
    {
        return (string)$other === (string)$this;
    }

    public function __toString()
    {
        return (string)$this->clientId;
    }
}
