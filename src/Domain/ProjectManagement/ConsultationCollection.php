<?php

namespace Mannion007\BestInvestments\Domain\ProjectManagement;

class ConsultationCollection implements \ArrayAccess, \Countable, \Traversable
{
    private $consultations = [];

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->consultations[] = $value;
        } else {
            $this->consultations[$offset] = $value;
        }
    }

    public function offsetExists($offset): bool
    {
        return isset($this->consultations[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->consultations[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->consultations[$offset]) ? $this->consultations[$offset] : null;
    }

    public function includes($key): bool
    {
        return $this->offsetExists($key);
    }

    public function count()
    {
        return count($this->consultations);
    }
}
