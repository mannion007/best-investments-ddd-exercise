<?php

namespace Mannion007\BestInvestments\Domain\ProjectManagement;

class SpecialistCollection implements \ArrayAccess, \Countable
{
    private $specialists = [];

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->specialists[] = $value;
        } else {
            $this->specialists[$offset] = $value;
        }
    }

    public function offsetExists($offset): bool
    {
        return isset($this->specialists[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->specialists[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->specialists[$offset]) ? $this->specialists[$offset] : null;
    }

    public function includes($key): bool
    {
        return $this->offsetExists($key);
    }

    public function count()
    {
        return count($this->specialists);
    }
}
