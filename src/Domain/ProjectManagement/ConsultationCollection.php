<?php

namespace Mannion007\BestInvestments\Domain\ProjectManagement;

class ConsultationCollection implements \IteratorAggregate, \Countable
{
    private $consultations = [];

    public function add(Consultation $consultation)
    {
        $this->consultations[(string)$consultation->getConsultationId()] = $consultation;
    }

    public function get(ConsultationId $consultationId): Consultation
    {
        if (!isset($this->consultations[(string)$consultationId])) {
            throw new \Exception('Consultation not in collection');
        }
        return $this->consultations[(string)$consultationId];
    }

    public function remove(Consultation $consultation)
    {
        $index = array_search($consultation, $this->consultations);
        if (!is_numeric($index)) {
            throw new \Exception('Cannot remove Consultation that is not in the collection');
        }
        unset($this->consultations[$index]);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->consultations);
    }

    public function contains(ConsultationId $consultationId): bool
    {
        return array_key_exists((string)$consultationId, $this->consultations);
    }

    public function count()
    {
        return count($this->consultations);
    }
}
