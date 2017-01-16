<?php

namespace Mannion007\BestInvestments\Domain\ProjectManagement;

class ProjectDrafted extends DomainEvent implements DomainEventInterface
{
    const EVENT_NAME = 'project_drafted';
    const DATE_FORMAT = 'c';

    private $reference;
    private $clientId;
    private $name;
    private $deadline;

    public function __construct(
        ProjectReference $reference,
        ClientId $clientId,
        string $name,
        \DateTime $deadline
    ) {
        parent::__construct();
        $this->reference = $reference;
        $this->clientId = $clientId;
        $this->name = $name;
        $this->deadline = $deadline;
    }

    public function getPayload(): array
    {
        return [
            'reference' => (string)$this->reference,
            'client_id' => (string)$this->reference,
            'name' => (string)$this->name,
            'deadline' => date_format($this->deadline, self::DATE_FORMAT)
        ];
    }

    public static function fromPayload(array $payload) : DomainEvent
    {
        return new self(
            ProjectReference::fromExisting($payload['reference']),
            ClientId::fromExisting($payload['client_id']),
            $payload['name'],
            \DateTime::createFromFormat(self::DATE_FORMAT, $payload['deadline'])
        );
    }
}
