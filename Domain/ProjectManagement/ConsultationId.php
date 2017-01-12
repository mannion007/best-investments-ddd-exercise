<?php

namespace Mannion007\BestInvestments\ProjectManagement;

class ConsultationId
{
    private $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function __toString()
    {
        return (string)$this->id;
    }
}
