<?php

namespace Mannion007\BestInvestments\Domain\Invoicing;

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

    public function is($status): bool
    {
        return $status === $this->status;
    }

    public function isNot($status): bool
    {
        return !$this->is($status);
    }
}
