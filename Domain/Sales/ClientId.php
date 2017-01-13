<?php

namespace Mannion007\BestInvestments\Domain\Sales;

use Ramsey\Uuid\Uuid;

class ClientId
{
    private $id;

    /** "new client ID" sounds OK, no need to hide default constructor */
    public function __construct()
    {
        $this->id = Uuid::uuid4()->toString();
    }

    public function isNot(ClientId $id) : bool
    {
        return !$this->is($id);
    }

    public function is(ClientId $id) : bool
    {
        return (string)$this === (string)$id;
    }

    public function __toString()
    {
        return (string)$this->id;
    }
}
