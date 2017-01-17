<?php

namespace Mannion007\BestInvestments\Domain\ProjectManagement;

use Mannion007\BestInvestments\Event\EventInterface;

class ConsultationScheduled implements EventInterface
{
    const EVENT_NAME = 'consultation_scheduled';
    const DATE_FORMAT = 'c';

    private $reference;
    private $specialistId;
    private $time;
    private $occurredAt;

    public function __construct(
        ProjectReference $reference,
        SpecialistId $specialistId,
        \DateTime $time,
        \DateTime $occurredAt = null
    ) {
        $this->reference = $reference;
        $this->specialistId = $specialistId;
        $this->time = $time;
        $this->occurredAt = is_null($occurredAt) ? new \DateTime() : $occurredAt;
    }

    public function getEventName() : string
    {
        return self::EVENT_NAME;
    }

    public function getOccurredAt() : \DateTime
    {
        return $this->occurredAt;
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

    public static function fromPayload(array $payload) : ConsultationScheduled
    {
        return new self(
            ProjectReference::fromExisting($payload['reference']),
            SpecialistId::fromExisting($payload['specialist_id']),
            \DateTime::createFromFormat(self::DATE_FORMAT, $payload['time'])
        );
    }
}
