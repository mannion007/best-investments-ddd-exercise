<?php

namespace Mannion007\BestInvestments\Domain\Invoicing;

class ClientId
{
    private $id;

    private function __construct($id)
    {
        $this->id = $id;
    }

    public static function fromExisting(string $id): ClientId
    {
        return new self($id);
    }

    public function isNot(ClientId $id): bool
    {
        return !$this->is($id);
    }

    public function is(ClientId $id): bool
    {
        return (string)$this === (string)$id;
    }

    public function __toString()
    {
        return (string)$this->id;
    }
}