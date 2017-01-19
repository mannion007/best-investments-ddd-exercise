<?php

namespace Mannion007\BestInvestments\Domain\Invoicing;

class PaymentReference
{
    private $reference;

    public function __construct(string $reference)
    {
        $this->reference = $reference;
    }
}