<?php

namespace Mannion007\BestInvestments\Domain\ProjectManagement;

use Mannion007\BestInvestments\Event\EventInterface;

class ProjectDrafted implements EventInterface
{
    const EVENT_NAME = 'project_drafted';
    const DATE_FORMAT = 'c';

    private $reference;
    private $clientId;
    private $name;
    private $deadline;
    private $occurredAt;

    public function __construct(
        ProjectReference $reference,
        ClientId $clientId,
        string $name,
        \DateTime $deadline,
        \DateTime $occurredAt = null
    ) {
        $this->reference = $reference;
        $this->clientId = $clientId;
        $this->name = $name;
        $this->deadline = $deadline;
        $this->occurredAt = is_null($occurredAt) ? new \DateTime() : $occurredAt;
    }

    public function getReference(): ProjectReference
    {
        return $this->reference;
    }

    public function getClientId(): ClientId
    {
        return $this->clientId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDeadline(): \DateTime
    {
        return $this->deadline;
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
            'client_id' => (string)$this->reference,
            'name' => (string)$this->name,
            'deadline' => date_format($this->deadline, self::DATE_FORMAT)
        ];
    }

    public static function fromPayload(array $payload) : ProjectDrafted
    {
        return new self(
            ProjectReference::fromExisting($payload['reference']),
            ClientId::fromExisting($payload['client_id']),
            $payload['name'],
            \DateTime::createFromFormat(self::DATE_FORMAT, $payload['deadline'])
        );
    }
}
