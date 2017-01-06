<?php

class SpecialistId
{
    private $id;

    public function __toString()
    {
        return (string)$this->id;
    }
}
