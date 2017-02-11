<?php

namespace Mannion007\BestInvestments\Invoicing\Domain;

class PaymentReference
{
    private $reference;

    public function __construct(string $reference)
    {
        $this->reference = $reference;
    }
}
