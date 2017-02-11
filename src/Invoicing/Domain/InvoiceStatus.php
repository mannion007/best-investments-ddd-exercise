<?php

namespace Mannion007\BestInvestments\Invoicing\Domain;

class InvoiceStatus
{
    const PAID = 'paid';
    const OUTSTANDING = 'outstanding';

    private $status;

    private function __construct(string $status)
    {
        $this->status = $status;
    }

    public static function paid(): InvoiceStatus
    {
        return new self(self::PAID);
    }

    public static function outstanding(): InvoiceStatus
    {
        return new self(self::OUTSTANDING);
    }

    public function isNot($status): bool
    {
        return !$this->is($status);
    }

    public function is($status): bool
    {
        return $status === $this->status;
    }
}
