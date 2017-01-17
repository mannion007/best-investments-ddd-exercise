<?php

namespace Mannion007\BestInvestments\Domain\Invoicing;

class TimeIncrement
{
    const MINUTES_PER_INCREMENT = 15;

    private $minutes = 0;

    public function __construct(int $minutes)
    {
        if ($minutes < 0) {
            throw new \DomainException('A Time Increment must have at least positive number of minutes');
        }
        $this->minutes = ceil($minutes / self::MINUTES_PER_INCREMENT);
    }

    public function add(TimeIncrement $timeToAdd)
    {
        return new self($this->minutes + $timeToAdd->inMinutes());
    }

    public function minus(TimeIncrement $timeToMinus)
    {
        if ($timeToMinus->inMinutes() > $this->inMinutes()) {
            throw new \DomainException('Cannot minus more time than the time increment has');
        }
        return new self($this->minutes - $timeToMinus->inMinutes());
    }

    public function isMoreThan(TimeIncrement $increment)
    {
        return $this->inMinutes() > $increment->inMinutes();
    }

    public function inMinutes() : int
    {
        return $this->minutes;
    }

    public function inHours() : float
    {
        return $this->minutes / 60;
    }
}
