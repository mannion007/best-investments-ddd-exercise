<?php

namespace Mannion007\BestInvestments\Domain\ProjectManagement;

use Mannion007\BestInvestments\Event\EventInterface;

class SpecialistPutOnListEvent implements EventInterface
{
    const EVENT_NAME = 'specialist_put_on_list';

    private $specialistId;
    private $projectManagerId;
    private $name;
    private $notes;
    private $occurredAt;

    public function __construct(
        SpecialistId $specialistId,
        ProjectManagerId $projectManagerId,
        string $name,
        string $notes,
        \DateTime $occurredAt = null
    ) {
        $this->specialistId = $specialistId;
        $this->projectManagerId = $projectManagerId;
        $this->name = $name;
        $this->notes = $notes;
        $this->occurredAt = is_null($occurredAt) ? new \DateTime() : $occurredAt;
    }

    public function getId(): SpecialistId
    {
        return $this->specialistId;
    }

    public function getProjectManagerId(): ProjectManagerId
    {
        return $this->projectManagerId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getNotes(): string
    {
        return $this->notes;
    }

    public function getEventName(): string
    {
        return self::EVENT_NAME;
    }

    public function getOccurredAt(): \DateTimeInterface
    {
        return $this->occurredAt;
    }

    public function getPayload(): array
    {
        return
        [
            'specialist_id' => (string)$this->specialistId,
            'project_manager_id' => (string)$this->projectManagerId,
            'name' => $this->name,
            'notes' => $this->notes
        ];
    }

    public static function fromPayload(array $payload): SpecialistPutOnListEvent
    {
        return new self(
            SpecialistId::fromExisting($payload['specialist_id']),
            ProjectManagerId::fromExisting($payload['project_manager_id']),
            $payload['name'],
            $payload['notes']
        );
    }
}
