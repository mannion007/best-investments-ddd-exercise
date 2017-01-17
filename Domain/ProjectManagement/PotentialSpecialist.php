<?php

namespace Mannion007\BestInvestments\Domain\ProjectManagement;

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

        DomainEventPublisher::publish(
            new SpecialistPutOnList($this->specialistId, $this->projectManagerId, $this->name, $this->notes)
        );
    }

    public static function putOnList(
        ProjectManagerId $projectManagerId,
        string $notes,
        string $name
    ): PotentialSpecialist {
        return new self($projectManagerId, $name, $notes);
    }

    public function register(Money $hourlyRate): Specialist
    {
        return Specialist::register($this->specialistId, $this->name, $hourlyRate);
    }
}
