<?php

namespace Mannion007\BestInvestments\Domain\Invoicing;

class ConsultationId
{
    private $id;

    private function __construct(int $id)
    {
        $this->id = $id;
    }

    public static function fromExisting(int $id)
    {
        return new self($id);
    }

    public function __toString()
    {
        return (string)$this->id;
    }
}
