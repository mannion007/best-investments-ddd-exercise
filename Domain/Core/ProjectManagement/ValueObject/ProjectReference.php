<?php

class ProjectReference
{
    private $reference;

    /** "new project reference" sounds OK, no need to hide default constructor */
    public function __construct()
    {
        $letters = sprintf('%s%s', chr(rand(97, 122)), chr(rand(97, 122)));
        $digits = str_pad((string)random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        $this->reference = $letters . $digits;
    }

    public function toString()
    {
        return (string)$this->reference;
    }
}
