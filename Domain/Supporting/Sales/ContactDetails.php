<?php

class ContactDetails
{
    private $telephone;

    public function __construct(string $telephone)
    {
        $this->telephone = $telephone;
    }

    public function getTelephone() : string
    {
        return $this->telephone;
    }
}
