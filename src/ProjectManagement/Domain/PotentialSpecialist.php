<?php

namespace Mannion007\BestInvestments\ProjectManagement\Domain;

use Mannion007\BestInvestments\EventPublisher\EventPublisher;

class PotentialSpecialist
{
    private $specialistId;
    private $projectManagerId;
    private $name;
    private $notes;

    private function __construct(
        ProjectManagerId $projectManagerId,
        string $name,
        string $notes
    ) {
        $this->specialistId = new SpecialistId();
        $this->projectManagerId = $projectManagerId;
        $this->name = $name;
        $this->notes = $notes;

        EventPublisher::publish(
            new SpecialistPutOnListEvent(
                (string)$this->specialistId,
                (string)$this->projectManagerId,
                $this->name,
                $this->notes
            )
        );
    }

    public static function putOnList(
        ProjectManagerId $projectManagerId,
        string $name,
        string $notes
    ): PotentialSpecialist {
        return new self($projectManagerId, $name, $notes);
    }

    public function register(HourlyRate $hourlyRate): Specialist
    {
        return new Specialist($this->specialistId, $this->name, $hourlyRate);
    }

    public function getSpecialistId(): SpecialistId
    {
        return $this->specialistId;
    }
}
