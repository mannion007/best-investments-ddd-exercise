<?php

class SpecialistShortlist
{
    private $specialists;

    public function __construct()
    {

    }

    public function includes($key)
    {
        return array_key_exists($key, $this->specialists);
    }
}