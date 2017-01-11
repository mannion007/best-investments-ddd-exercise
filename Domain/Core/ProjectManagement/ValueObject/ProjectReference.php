<?php

class ProjectReference
{
    private function __construct()
    {
    }

    public static function create()
    {
        return new self();
    }

    public function toString()
    {
        return '';
    }
}
