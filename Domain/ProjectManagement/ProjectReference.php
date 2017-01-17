<?php

namespace Mannion007\BestInvestments\Domain\ProjectManagement;

class ProjectReference
{
    private $reference;

    public function __construct(string $reference = null)
    {
        $this->reference = is_null($reference) ? $this->generate() : $reference;
    }

    public static function fromExisting(string $reference): ProjectReference
    {
        return new self($reference);
    }

    private function generate()
    {
        $letters = sprintf('%s%s', chr(rand(97, 122)), chr(rand(97, 122)));
        $digits = str_pad((string)random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        return sprintf('%s%s', $letters, $digits);
    }

    public function toString()
    {
        return (string)$this->reference;
    }
}
