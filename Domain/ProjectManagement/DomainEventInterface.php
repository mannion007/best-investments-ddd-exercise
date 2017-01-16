<?php

namespace Mannion007\BestInvestments\Domain\ProjectManagement;

interface DomainEventInterface
{
    public function getEventName() : string;
    public function getPayload() : array;
    public function getOccurredAt() : \DateTime;
    public static function fromPayload(array $payload) : DomainEvent;
}