<?php

namespace Mannion007\BestInvestments\Domain\ProjectManagement;

class SpecialistApproved implements DomainEventInterface
{
    const EVENT_NAME = 'specialist_approved';

    private $reference;
    private $specialistId;
    private $occurredAt;

    public function __construct(ProjectReference $reference, SpecialistId $specialistId, \DateTime $occurredAt = null)
    {
        $this->reference = $reference;
        $this->specialistId = $specialistId;
        $this->occurredAt = is_null($occurredAt) ? new \DateTime() : $occurredAt;
    }

    /**
     * @return ProjectReference
     */
    public function getReference(): ProjectReference
    {
        return $this->reference;
    }

    /**
     * @return SpecialistId
     */
    public function getSpecialistId(): SpecialistId
    {
        return $this->specialistId;
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
        return [
            'reference' => (string)$this->reference,
            'specialist_id' => (string)$this->specialistId
        ];
    }

    public static function fromPayload(array $payload) : SpecialistApproved
    {
        return new self(
            ProjectReference::fromExisting($payload['reference']),
            SpecialistId::fromExisting($payload['specialist_id'])
        );
    }
}
