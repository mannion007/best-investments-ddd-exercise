<?php

class ConsultationId
{
    private $id;

    /** Consultations are under the "Project" Agggregate Root, so they are given an ID */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function __toString()
    {
        return (string)$this->id;
    }
}
