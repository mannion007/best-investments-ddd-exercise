<?php

namespace Mannion007\BestInvestments\Domain\ProjectManagement;

class SpecialistCollection implements \IteratorAggregate, \Countable
{
    private $specialists = [];

    public function add(SpecialistId $specialistId)
    {
        $this->specialists[] = $specialistId;
    }

    public function remove(SpecialistId $specialistId)
    {
        $index = array_search($specialistId, $this->specialists);
        if (!is_numeric($index)) {
            throw new \Exception('Cannot remove Specialist that is not in the collection');
        }
        unset($this->specialists[$index]);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->specialists);
    }

    public function contains(SpecialistId $specialistId): bool
    {
        return is_numeric(array_search($specialistId, $this->specialists));
    }

    public function count()
    {
        return count($this->specialists);
    }
}
