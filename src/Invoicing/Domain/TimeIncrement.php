<?php

namespace Mannion007\BestInvestments\Invoicing\Domain;

class TimeIncrement
{
    const MINUTES_PER_INCREMENT = 15;

    private $increments = 0;

    public function __construct(int $minutes)
    {
        if ($minutes < 0) {
            throw new \Exception('A Time Increment must have a positive number of minutes');
        }
        $this->increments = (int)ceil($minutes / self::MINUTES_PER_INCREMENT);
    }

    public function add(TimeIncrement $timeToAdd): TimeIncrement
    {
        return new self($this->inMinutes() + $timeToAdd->inMinutes());
    }

    public function minus(TimeIncrement $timeToMinus)
    {
        if ($timeToMinus->inMinutes() > $this->inMinutes()) {
            throw new \Exception('Cannot minus more time than the time increment has');
        }
        return new self($this->inMinutes() - $timeToMinus->inMinutes());
    }

    public function isMoreThan(TimeIncrement $increment)
    {
        return $this->inMinutes() > $increment->inMinutes();
    }

    public function inMinutes(): int
    {
        return $this->increments * self::MINUTES_PER_INCREMENT;
    }
}
