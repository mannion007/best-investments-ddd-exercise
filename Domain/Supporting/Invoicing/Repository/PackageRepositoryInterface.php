<?php

interface PackageRepositoryInterface
{
    public function getByReference(PackageReference $reference);
}