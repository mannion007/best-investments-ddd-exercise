<?php

namespace Mannion007\BestInvestments\Domain\ProjectManagement;

class ProjectDraftedEvent
{
    const EVENT_NAME = 'project_drafted';
    const DATE_FORMAT = 'c';

    private $reference;

    public function __construct(ProjectReference $reference, ClientId $clientId, string $name, \DateTime $deadline)
    {
        $this->reference = $reference;
        $this->clientId = $clientId;
        $this->name = $name;
        $this->deadline = $deadline;
    }

    public static function fromPayload(array $payload)
    {
        return new self(
            ProjectReference::fromExisting($payload['reference']),
            ClientId::fromExisting($payload['client_id']),
            $payload['name'],
            \DateTime::createFromFormat(self::DATE_FORMAT, $payload['deadline'])
        );
    }

    public function jsonSerialize():array
    {
        return array(
            'reference' => $this->reference,
            'client_id' => $this->clientId,
            'name' => $this->name,
            'deadline' => date_format($this->deadline, self::DATE_FORMAT)
        );
    }
}
