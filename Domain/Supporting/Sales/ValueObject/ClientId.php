<?php

class ClientId
{
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function isNot(ClientId $id)
    {
        return !$this->is($id);
    }

    public function is(ClientId $id)
    {
        return (string)$this === (string)$id;
    }

    public function __toString()
    {
        return (string)$this->id;
    }
}
