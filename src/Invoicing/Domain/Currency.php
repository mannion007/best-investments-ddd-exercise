<?php

namespace Mannion007\BestInvestments\Invoicing\Domain;

class Currency
{
    private $code;

    private function __construct(string $code)
    {
        $this->code = $code;
    }

    public static function gbp()
    {
        return new self('gbp');
    }
    
    public function isNot(Currency $other)
    {
        return !$this->is($other);
    }

    public function is(Currency $other)
    {
        return $this->code === (string)$other;
    }

    public function __toString()
    {
        return (string)$this->code;
    }
}
