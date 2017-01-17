<?php

namespace Mannion007\BestInvestments\Domain\ProjectManagement;

class ConsultationScheduled extends DomainEvent implements DomainEventInterface
{
    const EVENT_NAME = 'consultation_scheduled';
    const DATE_FORMAT = 'c';

    private $reference;
    private $specialistId;
    private $time;

    public function __construct(ProjectReference $reference, SpecialistId $specialistId, \DateTime $time)
    {
        parent::__construct();
        $this->reference = $reference;
        $this->specialistId = $specialistId;
        $this->time = $time;
    }

    public function getPayload(): array
    {
        return
        [
            'reference' => (string)$this->reference,
            'specialist_id' => (string)$this->specialistId,
            'time' => date_format($this->time, self::DATE_FORMAT)
        ];
    }

    public static function fromPayload(array $payload) : DomainEvent
    {
        return new self(
            ProjectReference::fromExisting($payload['reference']),
            SpecialistId::fromExisting($payload['specialist_id']),
            \DateTime::createFromFormat(self::DATE_FORMAT, $payload['time'])
        );
    }
}
