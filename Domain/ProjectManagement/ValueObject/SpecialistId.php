<?php

class SpecialistId
{
    private $id;

    /** "new reference ID" sounds ok, no need to hide the default constructor */
    public function __construct()
    {
        $this->id = Ramsey\Uuid\Uuid::uuid4()->toString();
    }

    public function __toString()
    {
        return (string)$this->id;
    }
}
