<?php

class Specialist
{
    private $id;
    private $name;

    private function __construct(SpecialistId $id, string $name)
    {
        $this->id = new SpecialistId();
        $this->name = $name;
        /** Raise a 'specialist_registered' event */
    }

    public static function register(SpecialistId $id, string $name)
    {
        return new self($id, $name);
    }
}